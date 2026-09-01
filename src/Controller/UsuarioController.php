<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\RolRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    #[Route('/usuario', name: 'app_usuario')]
    #[Route('/usuario/', name: 'app_usuario_slash')]
    public function index(RolRepository $rolRepository): Response
    {
        return $this->render('usuario/index.html.twig', [
            'roles' => $rolRepository->findBy(['activo' => true]),
        ]);
    }

    #[Route('/usuario/listar', name: 'app_usuario_listar', methods: ['GET'])]
    public function listar(UsuarioRepository $usuarioRepository): JsonResponse
    {
    $usuarios = $usuarioRepository->findAll();
    $data = [];

    foreach ($usuarios as $u) {
        $data[] = [
            'id' => $u->getId(),
            'rol' => $u->getRol() ? $u->getRol()->getNombreRol() : 'Sin Rol',
            'nombre_completo' => $u->getNombreCompleto(),
            'correo_electronico' => $u->getCorreoElectronico(),
            'nombre_usuario' => $u->getNombreUsuario(),
            'activo' => $u->isActivo()
        ];
    }

    return $this->json(['data' => $data]);
    }

    #[Route('/usuario/guardar', name: 'app_usuario_guardar', methods: ['POST'])]
    public function guardar(Request $request, EntityManagerInterface $em, RolRepository $rolRepository): JsonResponse 
    {   
    try {
        $rolId = $request->request->get('rol_id');
        $nombreCompleto = $request->request->get('nombre_completo');
        $correo = $request->request->get('correo_electronico');
        $username = $request->request->get('nombre_usuario');
        $clave = $request->request->get('clave_acceso');
        $activo = $request->request->get('activo') === 'on' || $request->request->get('activo') === '1';

        // Validar campos obligatorios
        if (empty($nombreCompleto) || empty($correo) || empty($username) || empty($clave)) {
            return $this->json(['success' => false, 'message' => 'Llene todos los campos obligatorios.']);
        }

        $usuario = new Usuario();
        $usuario->setNombreCompleto($nombreCompleto);
        $usuario->setCorreoElectronico($correo);
        $usuario->setNombreUsuario($username);
        
        // Encriptación de contraseña
        $usuario->setClaveAcceso(password_hash($clave, PASSWORD_BCRYPT));
        $usuario->setActivo($activo);

        // Asignar el Rol si fue seleccionado
        if (!empty($rolId)) {
            $rol = $rolRepository->find($rolId);
            if ($rol) {
                $usuario->setRol($rol);
            }
        }

        $em->persist($usuario);
        $em->flush();

        return $this->json(['success' => true, 'message' => 'Usuario registrado correctamente.']);

    } catch (\Exception $e) {
        return $this->json([
            'success' => false, 
            'message' => 'Error en la base de datos: ' . $e->getMessage()
        ], 500);
    }
    }
}