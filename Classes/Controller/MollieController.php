<?php

namespace Passionweb\MollieApi\Controller;


use Passionweb\MollieApi\Service\MollieService;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MollieController extends ActionController
{
    public function __construct(
        protected MollieService $mollieService,
        protected string $successPid,
        protected LoggerInterface $logger
    )
    {
    }

    public function indexAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function paymentAction(): ResponseInterface
    {
        if($this->request->hasArgument('buyer') && $this->request->hasArgument('price')) {
           $checkoutUrl = $this->mollieService->createPayment(
                $this->request->getUri()->getScheme() . '://' . $this->request->getUri()->getHost(),
                (float)$this->request->getArgument('price'),
                $this->request->getArgument('buyer'),
                md5('order_' . time())
            );

            if (!empty($checkoutUrl)) {
                header('Location: ' . $checkoutUrl);
                exit;
            }
            $this->addFlashMessage('An unexpected error occured. Maybe you have not entered a val', 'Error during payment process', ContextualFeedbackSeverity::ERROR, false);
            return $this->getForwardResponse('index', $this->request->getArguments());
        }

        $this->addFlashMessage('.', 'Missing form data', ContextualFeedbackSeverity::ERROR, false);
        return $this->getForwardResponse('index', $this->request->getArguments());
    }

    public function paymentreturnAction(): ResponseInterface
    {
        // get order by order_id and do additional steps to handle the payment
        if(array_key_exists('order_id', $this->request->getAttribute('routing')->getArguments())) {
            // payment succeeded
            if($this->request->getAttribute('routing')->getPageId() === (int)$this->successPid) {
                $this->view->assign('success', true);
            }
            // payment failed
            else {
                $this->view->assign('failure', true);
            }
        }
        return $this->htmlResponse();
    }

    private function getForwardResponse(string $target, array $arguments = []): ForwardResponse
    {
        return (new ForwardResponse($target))
            ->withControllerName($this->request->getControllerName())
            ->withExtensionName($this->request->getControllerExtensionName())
            ->withArguments($arguments !== [] ? $arguments : $this->request->getArguments());
    }
}
