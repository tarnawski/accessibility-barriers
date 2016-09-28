<?php
namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CategoryController extends BaseController
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $categoryRepository = $this->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        return $this->success($categories, 'Category', Response::HTTP_OK, array('CATEGORY_LIST'));
    }

    /**
     * @param Category $category
     * @return Response
     * @ParamConverter("category", class="AccessibilityBarriersBundle\Entity\Category", options={"id" = "id"})
     */
    public function showAction(Category $category)
    {
        return $this->success($category, 'Category', Response::HTTP_OK, array('CATEGORY_DETAILS', 'NOTIFICATION_LIST'));
    }
}
