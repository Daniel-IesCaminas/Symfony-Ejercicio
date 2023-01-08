<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Repository\TareaRepository;
use App\Service\TareaManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TareaController extends AbstractController
{
    #[Route('/', name: 'app_listado_tarea')] 
    public function listado(TareaRepository $tareaRepository): Response
    {
        $tareas = $tareaRepository -> findAll();
        return $this->render('tarea/listado.html.twig', [
            'tareas' => $tareas,
        ]);
    }

    #[Route('/tarea/crear', name: 'app_crear_tarea')] 
    public function crear(TareaManager $tareaManager, Request $request): Response
    {
        $descripcion = $request->request->get('descripcion',null);
        $tarea = new Tarea();
        if (null !== $descripcion) {
                $tarea->setDescripcion($descripcion);
                $errores = $tareaManager->validar($tarea);

                if(empty($errores)){
                    $tareaManager->crear($tarea);
                    $this->addFlash('false', 'Tarea creada correctamente!');
                    return $this->redirectToRoute('app_listado_tarea');

                } else {
                    foreach($errores as $error) {
                        $this->addFlash('true',$error);
                    }
                }
        }
        return $this->render('tarea/crear.html.twig', [
            'tarea' => $tarea,
        ]);
    }

   
    #[Route('/tarea/eliminar/{id}', name: 'app_eliminar_tarea', requirements: ['id' => '\d+'] )] 
    public function eliminar(TareaManager $tareaManager,Tarea $tarea ): Response
    {
        $tareaManager->eliminar($tarea);
        $this->addFlash('false', 'Tarea eliminada correctamente!');
        return $this->redirectToRoute('app_listado_tarea');
    }

    #[Route('/tarea/editar-params/{id}', name: 'app_editar_con_params_convert', requirements: ['id' => '\d+'] )] 
    public function editarConParamsConvert(Tarea $tarea, TareaManager $tareaManager, Request $request): Response
    {
        $descripcion = $request->request->get('descripcion',null);
        if (null !== $descripcion) {
                $tarea->setDescripcion($descripcion);
                $errores = $tareaManager->validar($tarea);

                if(empty($errores)){
                    $tareaManager->editar($tarea);
                    $this->addFlash('false', 'Tarea editada correctamente!');
                    return $this->redirectToRoute('app_listado_tarea');
                } else {
                    foreach($errores as $error) {
                        $this->addFlash('true',$error);
                    }
                }
        }
        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea,
        ]);
    }


/*
    #[Route('/tarea/editar/{id}', name: 'app_editar_tarea', requirements: ['id' => '\d+'] )] 
    public function editar(int $id,TareaRepository $tareaRepository,  ManagerRegistry $doctrine, Request $request): Response
    {
        $tarea = $tareaRepository->findOneById($id);
        if(null === $tarea) {
            throw $this->createNotFoundException();
        }

        $descripcion = $request->request->get('descripcion',null);
        if (null !== $descripcion) {
            if(!empty($descripcion)){
                $tarea->setDescripcion($descripcion);
                $em = $doctrine->getManager();
                $em->flush();
                $this->addFlash('false', 'Tarea editada correctamente!');
                return $this->redirectToRoute('app_listado_tarea');
            }else {
                $this->addFlash('true', 'El campo descripcion es obligatorio');
            }
        }
        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea,
        ]);
    }
*/
}
