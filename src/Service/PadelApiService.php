<?php

namespace App\Service;
use AllowDynamicProperties;
use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

#[AllowDynamicProperties]
class PadelApiService
{
    private $client;
    private $apiToken;
    private $jugadorRepository;
    private $entityManager;
    private $logger;


    public function __construct(HttpClientInterface $client,
                                string $padelApiToken,
                                JugadorRepository $jugadorRepository,
                                EntityManagerInterface $entityManager,
                                LoggerInterface $logger)
    {
        $this->client = $client;
        $this->apiToken = $padelApiToken;
        $this->jugadorRepository = $jugadorRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function getJugadoresTopRanking(int $limit = 20)
    {
        $url = 'https://padelapi.org/api/players';

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'per_page' => $limit,
                    'sort_by' => 'ranking',
                    'order_by' => 'asc',
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception("Error API");
            }

            $data = $response->toArray();

            $jugadoresAPI = $data['data'] ?? [];

            $this->actualizarOCrearJugadores($jugadoresAPI);

            $jugadores = [];
            foreach ($jugadoresAPI as $p) {

                //Nombre
                $nombre = $p['name'] ?? 'Desconocido';

                //Puntos
                $puntos = $p['points'] ?? 0;

                //Foto
                if (!empty($p['photo_url'])) {
                    $imagen = $p['photo_url'];
                } else {
                    $imagen = 'https://ui-avatars.com/api/?name=' . urlencode($nombre) . '&background=random&color=fff&size=200';
                }

                //Side
                $lado = $p['side'] ?? '---';

                $jugadores[] = [
                    'id' => $this->jugadorRepository->findOneBy(['idApi' => $p['id']])->getId(),
                    'nombre' => $nombre,
                    'imagen' => $imagen,
                    'puntos' => $puntos,
                    'lado' => $lado,
                    'nacionalidad' => $p['nationality'] ?? 'Desconocido',
                ];
            }

            return $jugadores;

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function buscarJugadores(string $query)
    {
        $url = 'https://padelapi.org/api/players';

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'name' => $query,
                    'sort_by' => 'ranking',
                    'order_by' => 'asc',
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception("Error API");
            }

            $data = $response->toArray();

            $jugadoresAPI = $data['data'] ?? [];

            $this->actualizarOCrearJugadores($jugadoresAPI);

            $jugadores = [];
            foreach ($jugadoresAPI as $p) {

                //Nombre
                $nombre = $p['name'] ?? 'Desconocido';

                //Puntos
                $puntos = $p['points'] ?? 0;

                //Foto
                if (!empty($p['photo_url'])) {
                    $imagen = $p['photo_url'];
                } else {
                    $imagen = 'https://ui-avatars.com/api/?name=' . urlencode($nombre) . '&background=random&color=fff&size=200';
                }

                //Side
                $lado = $p['side'] ?? 'Desconocido';

                $jugadores[] = [
                    'id' => $this->jugadorRepository->findOneBy(['idApi' => $p['id']])->getId(),
                    'nombre' => $nombre,
                    'imagen' => $imagen,
                    'puntos' => $puntos,
                    'lado' => $lado,
                    'nacionalidad' => $p['nationality'] ?? 'Desconocido',
                ];
            }

            return $jugadores;

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function actualizarOCrearJugadores(array $jugadores): void{

        foreach ($jugadores as $jugador) {
            $idAPI = $jugador['id'];

            $jugadorBD = $this->jugadorRepository->findOneBy(['idApi' => $idAPI]);

            if (!$jugadorBD) {
                $jugadorBD = new Jugador();
                $jugadorBD->setIdApi($idAPI);
                $this->entityManager->persist($jugadorBD);
            }

            $jugadorBD->setNombre($jugador['name']);
            $jugadorBD->setSexo($jugador['category'] ?? 'Desconocido');
            $jugadorBD->setPuntos($jugador['points'] ?? 0);
            $jugadorBD->setImagenUrl($jugador['photo_url'] ?? null);
            $jugadorBD->setAltura($jugador['height'] ?? null);
            $jugadorBD->setActivo(true);

        }

        $this->entityManager->flush();
        $this->logger->notice("Jugadores añadidos o actualizados");

    }
}
