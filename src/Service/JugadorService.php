<?php
namespace App\Service;
use AllowDynamicProperties;
use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class JugadorService{
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

    public function obtenerDatosJugador(int $idJugador){

        $idApiJugador = $this->jugadorRepository->find($idJugador);

        if(!$idApiJugador){
            return null;
        }

        $url = 'https://padelapi.org/api/players/' . $idApiJugador->getIdApi();

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json',
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception("Error API");
            }

            $data = $response->toArray();

            //Nombre
            $nombre = $data['name'] ?? 'Desconocido';

            //Puntos
            $puntos = $data['points'] ?? 0;

            //Foto
            if (!empty($data['photo_url'])) {
                $imagen = $data['photo_url'];
            } else {
                $imagen = 'https://ui-avatars.com/api/?name=' . urlencode($nombre) . '&background=random&color=fff&size=200';
            }

            //Side
            $lado = $data['side'] ?? 'Desconocido';

            $jugador = [
                'id' => $this->jugadorRepository->findOneBy(['idApi' => $data['id']])->getId(),
                'nombre' => $nombre,
                'imagen' => $imagen,
                'puntos' => $puntos,
                'lado' => $lado,
                'fecha_nacimiento' => $data['birthdate'],
                'nacionalidad' => $data['nationality'] ?? 'Desconocido',
            ];

            $this->logger->info("Jugador:",$jugador);
            return $jugador;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }

    }
}
