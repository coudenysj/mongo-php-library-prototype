<?php

namespace MongoDB\Tests\Collection\CrudSpec;

use MongoDB\Collection;
use MongoDB\Operation\FindOneAndUpdate;

/**
 * CRUD spec functional tests for findOneAndUpdate().
 *
 * @see https://github.com/mongodb/specifications/tree/master/source/crud/tests
 */
class FindOneAndUpdateFunctionalTest extends FunctionalTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->createFixtures(3);
    }

    public function testFindOneAndUpdateWhenManyDocumentsMatchReturningDocumentBeforeModification()
    {
        $filter = array('_id' => array('$gt' => 1));
        $update = array('$inc' => array('x' => 1));
        $options = array(
            'projection' => array('x' => 1, '_id' => 0),
            'sort' => array('x' => 1),
        );

        $document = $this->collection->findOneAndUpdate($filter, $update, $options);
        $this->assertSameDocument(array('x' => 22), $document);

        $expected = array(
            array('_id' => 1, 'x' => 11),
            array('_id' => 2, 'x' => 23),
            array('_id' => 3, 'x' => 33),
        );

        $this->assertSameDocuments($expected, $this->collection->find());
    }

    public function testFindOneAndUpdateWhenManyDocumentsMatchReturningDocumentAfterModification()
    {
        $filter = array('_id' => array('$gt' => 1));
        $update = array('$inc' => array('x' => 1));
        $options = array(
            'projection' => array('x' => 1, '_id' => 0),
            'sort' => array('x' => 1),
            'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER,
        );

        $document = $this->collection->findOneAndUpdate($filter, $update, $options);
        $this->assertSameDocument(array('x' => 23), $document);

        $expected = array(
            array('_id' => 1, 'x' => 11),
            array('_id' => 2, 'x' => 23),
            array('_id' => 3, 'x' => 33),
        );

        $this->assertSameDocuments($expected, $this->collection->find());
    }

    public function testFindOneAndUpdateWhenOneDocumentMatchesReturningDocumentBeforeModification()
    {
        $filter = array('_id' => 2);
        $update = array('$inc' => array('x' => 1));
        $options = array(
            'projection' => array('x' => 1, '_id' => 0),
            'sort' => array('x' => 1),
        );

        $document = $this->collection->findOneAndUpdate($filter, $update, $options);
        $this->assertSameDocument(array('x' => 22), $document);

        $expected = array(
            array('_id' => 1, 'x' => 11),
            array('_id' => 2, 'x' => 23),
            array('_id' => 3, 'x' => 33),
        );

        $this->assertSameDocuments($expected, $this->collection->find());
    }

    public function testFindOneAndUpdateWhenOneDocumentMatchesReturningDocumentAfterModification()
    {
        $filter = array('_id' => 2);
        $update = array('$inc' => array('x' => 1));
        $options = array(
            'projection' => array('x' => 1, '_id' => 0),
            'sort' => array('x' => 1),
            'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER,
        );

        $document = $this->collection->findOneAndUpdate($filter, $update, $options);
        $this->assertSameDocument(array('x' => 23), $document);

        $expected = array(
            array('_id' => 1, 'x' => 11),
            array('_id' => 2, 'x' => 23),
            array('_id' => 3, 'x' => 33),
        );

        $this->assertSameDocuments($expected, $this->collection->find());
    }

    public function testFindOneAndUpdateWhenNoDocumentsMatchReturningDocumentBeforeModification()
    {
        $filter = array('_id' => 4);
        $update = array('$inc' => array('x' => 1));
        $options = array(
            'projection' => array('x' => 1, '_id' => 0),
            'sort' => array('x' => 1),
        );

        $document = $this->collection->findOneAndUpdate($filter, $update, $options);
        $this->assertNull($document);

        $expected = array(
            array('_id' => 1, 'x' => 11),
            array('_id' => 2, 'x' => 22),
            array('_id' => 3, 'x' => 33),
        );

        $this->assertSameDocuments($expected, $this->collection->find());
    }

    public function testFindOneAndUpdateWithUpsertWhenNoDocumentsMatchReturningDocumentBeforeModification()
    {
        $filter = array('_id' => 4);
        $update = array('$inc' => array('x' => 1));
        $options = array(
            'projection' => array('x' => 1, '_id' => 0),
            'sort' => array('x' => 1),
            'upsert' => true,
        );

        $document = $this->collection->findOneAndUpdate($filter, $update, $options);
        $this->assertNull($document);

        $expected = array(
            array('_id' => 1, 'x' => 11),
            array('_id' => 2, 'x' => 22),
            array('_id' => 3, 'x' => 33),
            array('_id' => 4, 'x' => 1),
        );

        $this->assertSameDocuments($expected, $this->collection->find());
    }

    public function testFindOneAndUpdateWhenNoDocumentsMatchReturningDocumentAfterModification()
    {
        $filter = array('_id' => 4);
        $update = array('$inc' => array('x' => 1));
        $options = array(
            'projection' => array('x' => 1, '_id' => 0),
            'sort' => array('x' => 1),
            'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER,
        );

        $document = $this->collection->findOneAndUpdate($filter, $update, $options);
        $this->assertNull($document);

        $expected = array(
            array('_id' => 1, 'x' => 11),
            array('_id' => 2, 'x' => 22),
            array('_id' => 3, 'x' => 33),
        );

        $this->assertSameDocuments($expected, $this->collection->find());
    }

    public function testFindOneAndUpdateWithUpsertWhenNoDocumentsMatchReturningDocumentAfterModification()
    {
        $filter = array('_id' => 4);
        $update = array('$inc' => array('x' => 1));
        $options = array(
            'projection' => array('x' => 1, '_id' => 0),
            'sort' => array('x' => 1),
            'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER,
            'upsert' => true,
        );

        $document = $this->collection->findOneAndUpdate($filter, $update, $options);
        $this->assertSameDocument(array('x' => 1), $document);

        $expected = array(
            array('_id' => 1, 'x' => 11),
            array('_id' => 2, 'x' => 22),
            array('_id' => 3, 'x' => 33),
            array('_id' => 4, 'x' => 1),
        );

        $this->assertSameDocuments($expected, $this->collection->find());
    }
}
