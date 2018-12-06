<?php

namespace App\Tests;
use App\Entity\Jury;
use App\Entity\Note;

use PHPUnit\Framework\TestCase;

class NoteTest extends TestCase
{
    public function testCreateJury()
    {
        $jury = new Jury();
        $jury->setName("thomas");
        $jury->setMail("thomas.millot08@gmail.com");
        $this->assertInternalType('string', $jury->getName());
        $this->assertNotNull($jury->getMail());
        return $jury;
    }

    public function testCreateNote()
    {
        $note = new Note();
        $note->setComment("Lorem ipsum dolor, sit amet consectetur adipisicing elit.
         Assumenda explicabo cum error esse, blanditiis autem! Nemo deleniti recusandae assumenda. Corrupti?");
        $note->setNote(8);
        $notevalue = (int)$note->getNote();
        $this->assertInternalType('string', $note->getComment());
        $this->assertInternalType('integer', $note->getNote());
        return $note;
    }

    public function testInsertNoteIntoJury()
    {
        $jury = $this->testCreateJury();
        $note = $this->testCreateNote();
        $note->setJury($jury);
        $this->assertInstanceOf(Jury::class, $note->getJury());
    }
}
