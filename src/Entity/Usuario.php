<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\Table(name: 'usuarios')]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Rol::class)]
    #[ORM\JoinColumn(name: 'rol_id', referencedColumnName: 'id', nullable: true)]
    private ?Rol $rol = null;

    #[ORM\Column(length: 100, nullable: false)]
    private ?string $nombreCompleto = null;

    #[ORM\Column(length: 100, unique: true, nullable: false)]
    private ?string $correoElectronico = null;

    #[ORM\Column(length: 50, unique: true, nullable: false)]
    private ?string $nombreUsuario = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $claveAcceso = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private ?bool $activo = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRol(): ?Rol
    {
        return $this->rol;
    }

    public function setRol(?Rol $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    public function getNombreCompleto(): ?string
    {
        return $this->nombreCompleto;
    }

    public function setNombreCompleto(string $nombreCompleto): static
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    public function getCorreoElectronico(): ?string
    {
        return $this->correoElectronico;
    }

    public function setCorreoElectronico(string $correoElectronico): static
    {
        $this->correoElectronico = $correoElectronico;

        return $this;
    }

    public function getNombreUsuario(): ?string
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario(string $nombreUsuario): static
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    public function getClaveAcceso(): ?string
    {
        return $this->claveAcceso;
    }

    public function setClaveAcceso(string $claveAcceso): static
    {
        $this->claveAcceso = $claveAcceso;

        return $this;
    }

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): static
    {
        $this->activo = $activo;

        return $this;
    }
}