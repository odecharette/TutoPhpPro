<?php

namespace Bookstore\DAO;

use Bookstore\Domain\Author;

class AuthorDAO extends DAO 
{
    /**
     * @var \Bookstore\DAO\BookDAO
     */
    private $bookDAO;

    public function setBookDAO(BookDAO $bookDAO) {
        $this->bookDAO = $bookDAO;
    }

    /**
     * Creates a, author object based on a DB row.
     *
     * @param array $row The DB row containing Author data.
     * @return \Bookstore\Domain\Author
     */
    protected function buildDomainObject($row) {
        $author = new Author();
        $author->setId($row['auth_id']);
        $author->setFirstName($row['auth_first_name']);
        $author->setLastName($row['auth_last_name']);
        return $author;
    }

    /**
     * Return Author information for the selected book
     *
     * @param integer $bookId The book id.
     *
     * @return Bookstore\Domain\Author
     */
    public function findAuthorOfBook($bookId) {
        // The associated book is retrieved only once
        $book = $this->bookDAO->find($bookId);

        $sql = "SELECT a.auth_id, a.auth_first_name, a.auth_last_name FROM author a,book b WHERE b.auth_id = a.auth_id AND b.book_id=?";
        $result = $this->getDb()->fetchAssoc($sql, array($bookId));

        if ($result)
            return $this->buildDomainObject($result);
        else
            throw new \Exception("No Author matching book id " . $bookId);

    }

}