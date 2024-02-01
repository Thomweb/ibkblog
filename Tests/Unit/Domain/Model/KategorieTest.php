<?php
namespace Ibk\Ibkblog\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Thomas Berscheid <thom@thomweb.de>
 */
class KategorieTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Ibk\Ibkblog\Domain\Model\Kategorie
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Ibk\Ibkblog\Domain\Model\Kategorie();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
            $this->subject
        );
    }
}
