<?php

namespace App\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Events;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

#[AsDoctrineListener(event: Events::postPersist, priority: 500)]
#[AsDoctrineListener(event: Events::postUpdate, priority: 500)]
#[AsDoctrineListener(event: Events::postRemove, priority: 500)]
class CacheClearListener
{
    private CacheItemPoolInterface $cache;
    private LoggerInterface $logger;

    public function __construct(CacheItemPoolInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->clearCache($args);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->clearCache($args);
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->clearCache($args);
    }

    private function clearCache($args): void
    {
        $entity = $args->getObject();
        $className = (new \ReflectionClass($entity))->getShortName();
        $cacheKey = 'App__Entity__' . $className;

        // Чистим кеш для измененной сущности
        $entityKeysItem = $this->cache->getItem($cacheKey . '.keys');
        if ($keys = $entityKeysItem->get()) {
            $this->cache->deleteItems($keys);
            $this->cache->deleteItem($entityKeysItem->getKey());
            $this->logger->info('Очистили кеш для ключей: ' . implode(', ', $keys));
        } else {
            $this->logger->info('Ошибка очистки кеша для: ' . $cacheKey);
        }

        // Убиваем кеш запросов
        $searchKeysItem = $this->cache->getItem('search.keys');
        if ($searchKeys = $searchKeysItem->get()) {
            $this->cache->deleteItems($searchKeys);
            $this->cache->deleteItem($searchKeysItem->getKey());
            $this->logger->info('Очистили кеш запросов');
        } else {
            $this->logger->info('Ошибка очистки кеша запросов');
        }

        // Добавляем вывод для отладки
        // echo 'Убили кеш для: ' . $className . '<br>';
    }
}
