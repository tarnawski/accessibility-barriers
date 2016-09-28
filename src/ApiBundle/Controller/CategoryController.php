<?php
namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

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
}
