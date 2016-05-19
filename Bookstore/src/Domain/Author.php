<?php

namespace Bookstore\Domain;

class Author 
{
    /**
     * Author id.
     *
     * @var integer
     */
    private $id;

    /**
     * Author first name
     *
     * @var string
     */
    private $first_name;

    /**
     * Author last name
     *
     * @var string
     */
    private $last_name;


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFirstName() {
        return $this->first_name;
    }

    public function setFirstName($first_name) {
        $this->first_name = $first_name;
    }

    public function getLastName() {
        return $this->last_name;
    }

    public function setLastName($last_name) {
        $this->last_name = $last_name;
    }

   
}