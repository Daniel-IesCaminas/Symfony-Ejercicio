<?php 

namespace App\Service;

use App\Entity\Tarea;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;

class TareaManager {

    private $em;
    private $tareaRepository;

    public function __construct(
        EntityManagerInterface $em,
        TareaRepository $tareaRepository
    ){
        $this->em = $em;
        $this->tareaRepository = $tareaRepository;
    }

    public function crear(Tarea $tarea) {
        $this->em->persist($tarea);
        $this->em->flush();
    }

    public function editar(Tarea $tarea): void {
        $this->em->flush();
    }

    public function eliminar(Tarea $tarea): void {
        $this->em->remove($tarea);
        $this->em->flush();
    }

    public function validar(Tarea $tarea) {
        $errores = [];
       if (empty($tarea->getDescripcion()))
            $errores[] = "Campo 'descripción' obligatorio";
        $tareaCondescripcionIgual = $this->tareaRepository->findOneByDescripcion($tarea->getDescripcion());
        if (null !== $tareaCondescripcionIgual && $tarea->getId() !== $tareaCondescripcionIgual->getId()) {
            $errores[] = "Descripción repetida";
        }
        return $errores;
    }


}




?>
