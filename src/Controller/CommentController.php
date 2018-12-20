<?php
    namespace App\Controller;

    use App\Entity\Comment;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;

    class CommentController extends Controller{


        /**
         * @Route("/", name="comment_list")
         * @Method({"GET"})
         */
        public function index(){
            $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
            return $this->render('comments/index.html.twig', array('comments'=> $comments));
        }



        /**
         * @Route("/comment/new", name="new_comment")
         * Method({"GET", "POST"})
         */
        public function new(Request $request){
            $comment = new Comment();

            $form = $this->createFormBuilder($comment)
                ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))

                ->add('body', TextareaType::class, array('required' => false, 'attr' => array('class' => 'form-control')))

                ->add('save', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary mt-3')))->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    $comment = $form->getData();

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($comment);
                    $entityManager->flush();

                    return $this->redirectToRoute('comment_list');
                }

            return $this->render('comments/new.html.twig', array('form' => $form->createView()));
        }


        /**
         * @Route("/comment/edit/{id}", name="edit_comment")
         * Method({"GET", "POST"})
         */
        public function edit(Request $request, $id){
            $comment = new Comment();

            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);

            $form = $this->createFormBuilder($comment)
                ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))

                ->add('body', TextareaType::class, array('required' => false, 'attr' => array('class' => 'form-control')))

                ->add('save', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary mt-3')))->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {


                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->flush();

                return $this->redirectToRoute('comment_list');
            }

            return $this->render('comments/edit.html.twig', array('form' => $form->createView()));
        }




        /**
         * @Route("/comment/{id}", name="comment_show")
         */
        public function show($id){
            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);

            return $this->render('comments/show.html.twig', array('comment'=>$comment));
        }

        /**
         * @Route("/comment/delete/{id}")
         * @Method({"DELETE"})
         */

        public function delete(Request $request, $id){
            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();

            $response = new Response();

            $response->send();

        }


    }