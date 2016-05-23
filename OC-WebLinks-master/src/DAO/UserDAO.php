<?php

namespace WebLinks\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use WebLinks\Domain\User;

class UserDAO extends DAO implements UserProviderInterface
{


    /**
     * Returns a list of all users, sorted by id.
     *
     * @return array A list of all users.
     */
    public function findAll() {
        $sql = "select * from t_user order by user_id desc";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['user_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Returns a User by id
     *
     * @return User
     */
    public function find($id) {
        $sql = "select * from t_user where user_id=" . $id;
        $result = $this->getDb()->fetchAssoc($sql, array($id));
        
        // Convert result into a domain objects
        if ($result)
            return $this->buildDomainObject($result);
        else
            throw new \Exception("No user matching id " . $id);
    }

    /**
     * Creates an User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \WebLinks\Domain\User
     */
    protected function buildDomainObject($row) {
        $user = new User();
        $user->setId($row['user_id']);
        $user->setUsername($row['user_name']);
        $user->setPassword($row['user_password']);
        $user->setSalt($row['user_salt']);
        $user->setRole($row['user_role']);
        
        return $user;
    }

     /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "select * from t_user where user_name=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'WebLinks\Domain\User' === $class;
    }
}
