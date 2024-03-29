<?php
namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Category;
use ApiBundle\Form\Type\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CategoryController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Return all categories"
     * )
     * @return Response
     */
    public function indexAction()
    {
        $categoryRepository = $this->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        return $this->success($categories, 'Category', Response::HTTP_OK, array('CATEGORY_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Return single category"
     * )
     * @param Category $category
     * @return Response
     * @ParamConverter("category", class="AccessibilityBarriersBundle\Entity\Category", options={"id" = "id"})
     */
    public function showAction(Category $category)
    {
        return $this->success($category, 'Category', Response::HTTP_OK, array('CATEGORY_DETAILS', 'NOTIFICATION_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Create new category",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Category name"}
     *  })
     * )
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CategoryType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        $category = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return $this->success($category, 'Category', Response::HTTP_CREATED, array('CATEGORY_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Update category",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Category name"}
     *  })
     * )
     * @param Request $request
     * @param Category $category
     * @return Response
     * @ParamConverter("category", class="AccessibilityBarriersBundle\Entity\Category", options={"id" = "id"})
     */
    public function updateAction(Request $request, Category $category)
    {
        $formData = json_decode($request->getContent(), true);
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }
        $category = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return $this->success($category, 'Category', Response::HTTP_OK, array('CATEGORY_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Delete category"
     * )
     * @param Category $category
     * @return Response
     * @ParamConverter("category", class="AccessibilityBarriersBundle\Entity\Category", options={"id" = "id"})
     */
    public function deleteAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->success(array('status' => 'Removed', 'message' => 'Category properly removed'), 'Category');
    }
}
