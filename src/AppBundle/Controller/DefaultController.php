<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    const ITEM_ON_PAGE = 500;

    /**
     * @Route("/{category}", defaults={"category"=null}, requirements={"category":"\w+"}, name="index_action")
     * @param string $category
     * @return Response
     */
    public function indexAction($category)
    {
        //Эту страницу стоит кэшировать nginx'ом.
        $categories = $this->get('app.repository.category')->loadTree();
        $error = true;
        foreach ($categories as $cat) {
            if ($cat->getUrl() == $category) {
                $error = false;
                break;
            }
        }
        if ($error) {
            throw $this->createNotFoundException('Категория не найдена');
        }
        if (!$category) {
            foreach ($categories as $cat) {
                if ($cat->isDefault()) {
                    $category = $cat->getUrl();
                }
            }
        }
        return $this->render('default/index.html.twig', [
            'categories' => $categories,
            'current'    => $category,
        ]);
    }

    /**
     * @Route(
     *     "/products/{categoryName}",
     *     defaults={"categoryName"=null},
     *     requirements={"categoryName":"\w+"},
     *     name="products_action"
     * )
     * @param Request $request
     * @param string $categoryName
     * @return JsonResponse
     */
    public function getProductsAction(Request $request, $categoryName)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Product');
        $category = $this->get('app.repository.category')->findOneBy(['url' => $categoryName]);
        $offset = ((int)$request->get('page', 1) - 1) * self::ITEM_ON_PAGE;
        try {
            return new JsonResponse(
                $repo->findByCategory($category, self::ITEM_ON_PAGE, $offset, $request->get('search'))
            );
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => true], 500);
        }
    }
}
