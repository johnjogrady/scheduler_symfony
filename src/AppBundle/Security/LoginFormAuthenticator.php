<?php

/**
 * Created by PhpStorm.
 * User: john.ogrady
 * Date: 30/03/2017
 * Time: 22:33
 */
namespace AppBundle\Security;


use AppBundle\Form\LoginForm;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;
    private $formFactory;

    private $em;

    private $router;

    /**
     * LoginFormAuthenticator constructor.
     */
    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');

        if (!$isLoginSubmit) {
            return;
        }
        $session = $request->getSession();
        $session->start();

        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);
        $data = $form->getData();

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );
        return $data;


    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];

        return $this->em->getRepository('AppBundle\Entity\User')
            ->findOneBy(['email' => $username]);

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }
        return false;
    }

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');

    }

    /**
     * Set defaultTargetPath
     *
     * @param string $defaultTargetPath
     * @return self
     */
    public function setDefaultTargetPath($defaultTargetPath)
    {
        $this->defaultTargetPath = $defaultTargetPath;
        return $this;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = null;

        if ($request->getSession() instanceof SessionInterface) {
            if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
                $this->removeTargetPath($request->getSession(), $providerKey);
            }
        }

        if (!$targetPath) {
            if (!$this->defaultTargetPath) {
                throw new \LogicException('No previous target path found in session and no default target path set');
            }

            $targetPath = $this->defaultTargetPath;
            $session = $request->getSession();
            $session->getFlashBag('notice');
            $session->getFlashBag()->add('notice', 'Success, you are now logged in!');

            if (strpos($targetPath, 'http' !== 0 && $targetPath[0] !== '/')) {
                $targetPath = $this->router->generate($targetPath);
            }
        }

        return new RedirectResponse($targetPath);

    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {


        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN);
    }
}
