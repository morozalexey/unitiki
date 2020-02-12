<?php
namespace App;

use Aura\SqlQuery\QueryFactory;

use PDO;

class queryBuilder 
{
	private $pdo;
	private $queryFactory;

	public function __construct(PDO $pdo, QueryFactory $queryFactory)
	{
		$this->pdo = $pdo;
		$this->queryFactory = $queryFactory;
    }
    
    public function getAllPosts()
	{	
        $select = $this->queryFactory->newSelect();

		$select->cols(["posts.*", "categories.category_name"])
		->from("posts")
		->leftJoin("categories", "posts.category_id = categories.id")
		->where("posts.hide = 1")        
		->orderBy(["id DESC"]);
        //->limit(10)

		$sth = $this->pdo->prepare($select->getStatement());
		$sth->execute($select->getBindValues());
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

    public function getPostsByCategory($table, $category_id)
    {

        $select = $this->queryFactory->newSelect();
        //posts.*", "categories.category_name"
        //, "categories"
        $select->cols(["*"])
        ->from("posts")
        ->leftJoin("categories", "posts.category_id = categories.id")
        ->where('category_id = :category_id')
        ->bindValue('category_id', $category_id);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function getOne($table, $id) 
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id',$id);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

	public function getAll($table)
	{
		$select = $this->queryFactory->newSelect();

		$select->cols(['*'])
		->from($table);		

		$sth = $this->pdo->prepare($select->getStatement());

		$sth->execute($select->getBindValues());

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}	

    public function insert($table, $data) 
    {
        $insert = $this->queryFactory->newInsert();
        $insert
            ->into($table)                   
            ->cols($data);
        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());
    }

    public function delete($table, $id) 
    {
        $delete = $this->queryFactory->newDelete();
        $delete->from($table)
            ->where('id = :id')
            ->bindValue('id',$id);
        
        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
    }

        public function update($table,$id,$data)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)                 
            ->cols($data)
            ->where('id = :id')           
            ->bindValue('id', $id);   
        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());            
    }
	
}