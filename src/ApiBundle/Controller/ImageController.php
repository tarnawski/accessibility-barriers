<?php

namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Service\FileUploadService;
use ApiBundle\Form\Type\ImageType;
use AccessibilityBarriersBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class ImageController
 */
class ImageController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Upload new Image",
     *  parameters={
     *      {"name"="file", "dataType"="string", "required"=true, "description"="base64"}
     *  })
     * )
     * @param Request $request
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(ImageType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        /** @var \ApiBundle\Model\Image $imageData */
        $imageData = $form->getData();

        /** @var FileUploadService $fileUploadService */
        $fileUploadService = $this->get('accessibility_barriers.services.file_upload_service');
        $fileName = $fileUploadService->upload($imageData->file);

        $image = new Image();
        $image->setName($fileName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();

        return $this->success($image, 'Image', Response::HTTP_CREATED, array('IMAGE_BASIC'));
    }

    /**
     * @ApiDoc(
     *  description="Delete image"
     * )
     * @param Image $image
     * @return Response
     * @ParamConverter("image", class="AccessibilityBarriersBundle\Entity\Image", options={"id" = "id"})
     */
    public function deleteAction(Image $image)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        return $this->success(array('status' => 'Removed', 'message' => 'Image properly removed'), 'Image');
    }
}
