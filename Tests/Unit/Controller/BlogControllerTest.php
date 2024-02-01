<?php
namespace Ibk\Ibkblog\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Berscheid <thom@thomweb.de>
 */
class BlogControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Ibk\Ibkblog\Controller\BlogController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Ibk\Ibkblog\Controller\BlogController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllBlogsFromRepositoryAndAssignsThemToView()
    {

        $allBlogs = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $blogRepository = $this->getMockBuilder(\Ibk\Ibkblog\Domain\Repository\BlogRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $blogRepository->expects(self::once())->method('findAll')->will(self::returnValue($allBlogs));
        $this->inject($this->subject, 'blogRepository', $blogRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('blogs', $allBlogs);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenBlogToView()
    {
        $blog = new \Ibk\Ibkblog\Domain\Model\Blog();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('blog', $blog);

        $this->subject->showAction($blog);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenBlogToBlogRepository()
    {
        $blog = new \Ibk\Ibkblog\Domain\Model\Blog();

        $blogRepository = $this->getMockBuilder(\Ibk\Ibkblog\Domain\Repository\BlogRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $blogRepository->expects(self::once())->method('add')->with($blog);
        $this->inject($this->subject, 'blogRepository', $blogRepository);

        $this->subject->createAction($blog);
    }
}
