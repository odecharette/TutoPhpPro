<?php

namespace WebLinks\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User  implements UserInterface
{
	/**
     * User id.
     *
     * @var integer
     */
    private $id;

    /**
     * User name.
     *
     * @var string
     */
    private $name;

    /**
     * User password.
     *
     * @var string
     */
    private $password;

    /**
     * User salt.
     *
     * @var string
     */
    private $salt;

    /**
     * User role.
     *
     * @var string
     */
    private $role;



    /**
     * Gets the User id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the User id.
     *
     * @param integer $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the User name.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->name;
    }

    /**
     * Sets the User name.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setUsername($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the User password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the User password.
     *
     * @param string $password the password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Gets the User salt.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Sets the User salt.
     *
     * @param string $salt the salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Gets the User role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Sets the User role.
     *
     * @param string $role the role
     *
     * @return self
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->getRole());
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        // Nothing to do here
    }
}