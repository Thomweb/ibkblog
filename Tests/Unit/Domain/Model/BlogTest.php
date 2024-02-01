<?php
namespace Ibk\Ibkblog\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Thomas Berscheid <thom@thomweb.de>
 */
class BlogTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Ibk\Ibkblog\Domain\Model\Blog
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Ibk\Ibkblog\Domain\Model\Blog();
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

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'email',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTitelReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitel()
        );
    }

    /**
     * @test
     */
    public function setTitelForStringSetsTitel()
    {
        $this->subject->setTitel('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'titel',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getKurzfassungReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getKurzfassung()
        );
    }

    /**
     * @test
     */
    public function setKurzfassungForStringSetsKurzfassung()
    {
        $this->subject->setKurzfassung('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'kurzfassung',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getInhaltReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getInhalt()
        );
    }

    /**
     * @test
     */
    public function setInhaltForStringSetsInhalt()
    {
        $this->subject->setInhalt('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'inhalt',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDatumReturnsInitialValueForDateTime()
    {
        self::assertEquals(
            null,
            $this->subject->getDatum()
        );
    }

    /**
     * @test
     */
    public function setDatumForDateTimeSetsDatum()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setDatum($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'datum',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLinkReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLink()
        );
    }

    /**
     * @test
     */
    public function setLinkForStringSetsLink()
    {
        $this->subject->setLink('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'link',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getKategorieReturnsInitialValueForKategorie()
    {
        self::assertEquals(
            null,
            $this->subject->getKategorie()
        );
    }

    /**
     * @test
     */
    public function setKategorieForKategorieSetsKategorie()
    {
        $kategorieFixture = new \Ibk\Ibkblog\Domain\Model\Kategorie();
        $this->subject->setKategorie($kategorieFixture);

        self::assertAttributeEquals(
            $kategorieFixture,
            'kategorie',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTagsReturnsInitialValueForTag()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getTags()
        );
    }

    /**
     * @test
     */
    public function setTagsForObjectStorageContainingTagSetsTags()
    {
        $tag = new \Ibk\Ibkblog\Domain\Model\Tag();
        $objectStorageHoldingExactlyOneTags = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneTags->attach($tag);
        $this->subject->setTags($objectStorageHoldingExactlyOneTags);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneTags,
            'tags',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addTagToObjectStorageHoldingTags()
    {
        $tag = new \Ibk\Ibkblog\Domain\Model\Tag();
        $tagsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $tagsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($tag));
        $this->inject($this->subject, 'tags', $tagsObjectStorageMock);

        $this->subject->addTag($tag);
    }

    /**
     * @test
     */
    public function removeTagFromObjectStorageHoldingTags()
    {
        $tag = new \Ibk\Ibkblog\Domain\Model\Tag();
        $tagsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $tagsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($tag));
        $this->inject($this->subject, 'tags', $tagsObjectStorageMock);

        $this->subject->removeTag($tag);
    }
}
