<?php 

namespace App\DataPersister;

use App\Entity\Etudiant;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class EtudiantDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports($data, array $context = []): bool
    {
        dd($data);
        return $data instanceof Etudiant;
    }

    public function persist($data, array $context = [])
    {
        dd($data);
        return $data;
    }

    public function remove($data, array $context = [])
    {
      // call your persistence layer to delete $data
    }
}
?>