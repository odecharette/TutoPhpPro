<?php

namespace WebLinks\DAO;

use WebLinks\Domain\Link;

class LinkDAO extends DAO 
{

    /**
     * @var \WebLinks\DAO\UserDAO
     */
    private $userDAO;

    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }


    /**
     * Returns a list of all links, sorted by id.
     *
     * @return array A list of all links.
     */
    public function findAll() {
        $sql = "select * from t_link order by link_id desc";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['link_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Returns a link by id
     *
     * @return Link
     */
    public function find($id) {
        $sql = "select * from t_link where link_id=" . $id;
        $result = $this->getDb()->fetchAssoc($sql);
        
        // Convert result into a domain objects
        return $this->buildDomainObject($result);
    }

    /**
     * Creates an Link object based on a DB row.
     *
     * @param array $row The DB row containing Link data.
     * @return \WebLinks\Domain\Link
     */
    protected function buildDomainObject($row) {
        $link = new Link();
        $link->setId($row['link_id']);
        $link->setUrl($row['link_url']);
        $link->setTitle($row['link_title']);

        if (array_key_exists('user_id', $row)) {
            // Find and set the associated user
            $userId = $row['user_id'];
            $user = $this->userDAO->find($userId);
            $link->setUser($user);
        }
        
        return $link;
    }

    /**
     * Saves a link into the database.
     *
     * @param \WebLinks\Domain\Link $link The link to save
     */
    public function save(Link $link) {
        $linkData = array(
            'link_id' => $link->getId(),
            'link_title' => $link->getTitle(),
            'link_url' => $link->getUrl(),
            'user_id' => $link->getUser()->getId()
            );

        // if ($link->getId()) {
        //     // The comment has already been saved : update it
        //     $this->getDb()->update('t_comment', $commentData, array('com_id' => $comment->getId()));
        // } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('t_link', $linkData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $link->setId($id);
        //}
    }
}
