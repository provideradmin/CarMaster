<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class SearchController extends AbstractController
{
    private $cache;
    private $entityManager;
    private $logger;

    public function __construct(
        CacheItemPoolInterface $cache,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/search', name: 'search')]
    public function search(Request $request): Response
    {
        $query = $request->query->get('query');
        $cacheKey = 'search_' . $query;
        $fromCache = false;

        $this->logger->info('Search query received', ['query' => $query]);

        $cacheItem = $this->cache->getItem($cacheKey);
        if ($cacheItem->isHit()) {
            $results = $cacheItem->get();
            $fromCache = true;
            $this->logger->info('Cache hit', ['results' => $results]);
        } else {
            $results = [];
            $entities = [
                'Client' => ['name', 'email', 'phone'],
                'Car' => ['type', 'brand', 'model', 'number'],
                'Material' => ['name'],
                'Order' => ['id'],
                'Part' => ['name'],
                'Service' => ['name']
            ];

            foreach ($entities as $entity => $fields) {
                $repository = $this->entityManager->getRepository('App\Entity\\' . $entity);
                $qb = $repository->createQueryBuilder('e');

                foreach ($fields as $field) {
                    $qb->orWhere("e.$field LIKE :query");
                }

                $qb->setParameter('query', '%' . $query . '%');
                $resultsRaw = $qb->getQuery()->getResult();

                foreach ($resultsRaw as $result) {
                    if ($entity === 'Car') {
                        $results[] = [
                            'name' => $result->getBrand() . ' ' . $result->getModel(),
                            'url' => '/' . strtolower($entity) . 's/' . $result->getId()
                        ];
                    } else {
                        $results[] = [
                            'name' => method_exists($result, 'getName') ? $result->getName() : (string) $result->getId(),
                            'url' => '/' . strtolower($entity) . 's/' . $result->getId()
                        ];
                    }
                }
            }

            $cacheItem->set($results);
            $this->cache->save($cacheItem);
            $this->logger->info('Cache set', ['results' => $results]);

            // Save cache keys
            $keysItem = $this->cache->getItem('search.keys');
            $keys = $keysItem->isHit() ? $keysItem->get() : [];
            $keys[] = $cacheKey;
            $keysItem->set($keys);
            $this->cache->save($keysItem);
        }

        return $this->render('search/results.html.twig', [
            'query' => $query,
            'results' => $results,
            'fromCache' => $fromCache,
        ]);
    }
}
