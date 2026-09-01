<?php

namespace App\Controller;

use App\Entity\Rol;
use App\Repository\RolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RolController extends AbstractController
{
    #[Route('/rol', name: 'app_rol')]
    #[Route('/rol/', name: 'app_rol_slash')]
    public function index(): Response
    {
        return $this->render('rol/index.html.twig', [
            'controller_name' => 'RolController',
        ]);
    }

    #[Route('/rol/listar', name: 'app_rol_listar', methods: ['GET'])]
    public function listar(RolRepository $rolRepository): JsonResponse
    {
        $roles = $rolRepository->findAll();
        $data = [];

        foreach ($roles as $rol) {
            $data[] = [
                'id' => $rol->getId(),
                'nombre_rol' => $rol->getNombreRol(),
                'descripcion' => $rol->getDescripcion() ?? '',
                'activo' => $rol->isActivo()
            ];
        }

        return $this->json(['data' => $data]);
    }

    #[Route('/rol/guardar', name: 'app_rol_guardar', methods: ['POST'])]
    public function guardar(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $nombreRol = $request->request->get('nombre_rol');
        $descripcion = $request->request->get('descripcion');
        $activo = $request->request->get('activo') === 'on';

        if (empty($nombreRol)) {
            return $this->json(['success' => false, 'message' => 'El nombre del rol es obligatorio.']);
        }

        $rol = new Rol();
        $rol->setNombreRol($nombreRol);
        $rol->setDescripcion($descripcion);
        $rol->setActivo($activo);

        $entityManager->persist($rol);
        $entityManager->flush();

        return $this->json(['success' => true, 'message' => 'Rol guardado correctamente.']);
    }
}