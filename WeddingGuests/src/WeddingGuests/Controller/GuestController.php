<?php
/**
 * Created by PhpStorm.
 * User: b_ven
 * Date: 19-5-2017
 * Time: 22:03
 */

namespace WeddingGuests\Controller;

use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use WeddingGuests\Entity\AfternoonGuest;
use WeddingGuests\Entity\DayGuest;
use WeddingGuests\Entity\EveningGuest;
use WeddingGuests\Classes\Evening;
use WeddingGuests\Classes\Afternoon;
use WeddingGuests\Classes\Day;
use WeddingGuests\Repository\GuestRepository;

class GuestController
{
    /**
     * @var GuestRepository
     */
    private $guestRepository;

    /**
     * GuestController constructor.
     * @param GuestRepository $guestRepository
     */
    public function __construct(GuestRepository $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }

    /**
     * @param Request $request
     * @param Application $app
     */
    public function viewGuests(Request $request, Application $app)
    {
        $guests = $this->guestRepository->findAll();

        $navigation = $app['naviation'];
        $navigation['list']['class'] = 'active';

        return $app['twig']->render(
            'guestlist.html.twig',
            [
                'navigation' => $navigation,
                'guests' => $guests
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function addGuest(Request $request, Application $app)
    {
        $form = $app['form.factory']->createBuilder(FormType::class)
            ->add('firstName', TextType::class, [
                'label' => 'Voornaam'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Achternaam'
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Dag' => 'Day',
                    'Middag' => 'Afternoon',
                    'Avond' => 'Evening'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Opslaan',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->saveGuestData($form, $app);
        }

        $navigation = $app['naviation'];
        $navigation['add']['class'] = 'active';

        return $app['twig']->render(
            'guestadd.html.twig',
            [
                'navigation' => $navigation,
                'form' => $form->createView()
            ]);
    }

    /**
     * @param Form $form
     * @param Application $app
     * @param array|null $guest
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveGuestData($form, Application $app, array $guestData = null)
    {
        $data = $form->getData();
        switch ($data['type']) {
            case 'Evening':
                $guest = new EveningGuest(new Evening());
                break;
            case 'Afternoon':
                $guest = new AfternoonGuest(new Afternoon());
                break;
            default:
                $guest = new DayGuest(new Day());
                break;
        }
        if ($guestData !== null) {
            $guest->setId($guestData['id']);
        }
        $guest->setFirstName($data['firstName']);
        $guest->setLastName($data['lastName']);

        $this->guestRepository->save($guest);
        return $app->redirect('/guest/list');
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return mixed
     */
    public function editGuest(Request $request, Application $app, $id)
    {
        $guest = $this->guestRepository->find($id);
        if ($guest === false) {
            return $app['twig']->render('entititynotfound.html.twig',
                [
                    'navigation' => $app['naviation']
                ]);
        }
        $form = $app['form.factory']->createBuilder(FormType::class)
            ->add('firstName', TextType::class, [
                'label' => 'Voornaam',
                'data' => $guest['firstName']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Achternaam',
                'data' => $guest['lastName']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Opslaan',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->saveGuestData($form, $app, $guest);
        }

        return $app['twig']->render(
            'guestadd.html.twig',
            [
                'navigation' => $app['naviation'],
                'form' => $form->createView()
            ]);
    }

    /**
     * @param Application $app
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteGuest(Application $app, $id) {
        $guest = new DayGuest(new Day());
        $guest->setId($id);
        $this->guestRepository->delete($guest);
        return $app->redirect('/guest/list');
    }
}