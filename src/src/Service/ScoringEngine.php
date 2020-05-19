<?php


namespace App\Service;


use App\Entity\Client;
use App\Entity\Score;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class ScoringEngine
{
    private $logger;
    private $em;
    /** @var Client */
    private $client;
    private $scorePoints = 0;

    private static $operatorCode = [
        "mts" => [910, 915, 916, 917, 919, 985, 986],
        "megafon" => [925, 926, 929, 936, 999],
        "bilain" => [903, 905, 906, 909, 962, 963, 964, 965, 966, 967, 968, 969, 980, 983, 986]
    ];

    private static $operators = [
        "megafon" => 10,
        "bilain" => 5,
        "mts" => 3
    ];

    private static $emailDomain = [
        "gmail" => 10,
        "yandex" => 8,
        "mail" => 6
    ];

    private static $education = [
        3 => 15,
        2 => 10,
        1 => 5
    ];

    private static $dataProcess = [
        1 => 4,
        0 => 0
    ];

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->em = $entityManager;
        $this->logger = $logger;
    }

    public function calculate(Client $client)
    {
        $scoreRepository = $this->em->getRepository(Score::class);
        $score = $scoreRepository->findOneBy(['client' => $client]);
        if (!$score) {
            $score = new Score();
        }

        $this->client = $client;
        $score->setClient($client);
        $this->logger->info(sprintf("Calculating score for Client ID: %d", [$client->getId()]));
        $this->calculateAllPoints();
        $score->setResult($this->getScorePoints());
        $this->em->persist($score);
        $this->em->flush();
    }

    private function calculateAllPoints(): void
    {
        $this->calculateEmailDomainPoints();
        $this->calculateEducationPoints();
        $this->calculateDataProcessPoints();
        $this->calculateOperatorPoints();
    }

    private function calculateEmailDomainPoints(): void
    {
        $email = $this->client->getEmail();
        $fullDomain = substr($email, strpos($email, '@') + 1);
        $domain = explode(".", $fullDomain)[0];

        if (!array_key_exists($domain, self::$emailDomain)) {
            $this->setScorePoints(3);
            return;
        }
        $this->setScorePoints(self::$emailDomain[$domain]);
    }

    private function calculateEducationPoints(): void
    {
        $educationId = $this->client->getEducation()->getId();

        if (!array_key_exists($educationId, self::$education)) {
            new Exception("Incorrect Education ID");
        }
        $this->setScorePoints(self::$education[$educationId]);
    }

    private function calculateDataProcessPoints(): void
    {
        $processAllowed = intval($this->client->getProcessData());
        if (!array_key_exists($processAllowed, self::$dataProcess)) {
            new Exception("Incorrect value for Data Processing");
        }
        $this->setScorePoints(self::$dataProcess[$processAllowed]);
    }

    private function calculateOperatorPoints(): void
    {
        $phone = $this->client->getPhone();
        $operatorCode = substr($phone, 0, 3);

        $operator = $this->searchOperator($operatorCode);

        if (!array_key_exists($operator, self::$operators)) {
            $this->setScorePoints(1);
            return;
        }
        $this->setScorePoints(self::$operators[$operator]);
    }

    private function searchOperator($code)
    {
        $operator = null;
        foreach (self::$operatorCode as $key => $value) {
            $result = array_search($code, $value);
            if (!$result) {
                continue;
            }
            $operator = $key;
            break;
        }
        return $operator;
    }

    /**
     * @param $scorePoints
     */
    protected function setScorePoints($scorePoints): void
    {
        $currentPoints = $this->getScorePoints();
        $this->scorePoints = $scorePoints + $currentPoints;
    }

    /**
     * @return int
     */
    protected function getScorePoints(): int
    {
        return $this->scorePoints;
    }

}