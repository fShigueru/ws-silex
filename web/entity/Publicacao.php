<?php
/**
 * Created by PhpStorm.
 * User: Webelven01
 * Date: 02/10/2015
 * Time: 13:52
 */

namespace Entity;

class Publicacao
{

    private $id;
    private $usuarioId;
    private $usuarioNome;
    private $descricao;
    private $fotoWS;
    private $fotoLocal;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * @param mixed $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * @return mixed
     */
    public function getUsuarioNome()
    {
        return $this->usuarioNome;
    }

    /**
     * @param mixed $usuarioNome
     */
    public function setUsuarioNome($usuarioNome)
    {
        $this->usuarioNome = $usuarioNome;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return mixed
     */
    public function getFotoWS()
    {
        return $this->fotoWS;
    }

    /**
     * @param mixed $fotoWS
     */
    public function setFotoWS($fotoWS)
    {
        $this->fotoWS = $fotoWS;
    }

    /**
     * @return mixed
     */
    public function getFotoLocal()
    {
        return $this->fotoLocal;
    }

    /**
     * @param mixed $fotoLocal
     */
    public function setFotoLocal($fotoLocal)
    {
        $this->fotoLocal = $fotoLocal;
    }




}