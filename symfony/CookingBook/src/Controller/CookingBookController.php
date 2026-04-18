<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class CookingBookController extends AbstractController
{
    #[Route('/', name: 'recipe_list')]
    public function index(SessionInterface $session): Response
    {
        $default_recipe = [["name" => "Pancakes", "category" => "Breakfast", "time" => "20 min", "ingredients" => "flour, milk, eggs", "instructions" => "Mix and fry"]];
        $recipes = $session->get('recipes', $default_recipe);
        return $this->render('cooking_book/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route("/add", name:"add_recipe")]
    public function add_recipe(SessionInterface $session, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $recipes = $session->get("recipes", []);
            $recipes[] =
            [
                "name" => $request -> request -> get('name'),
                "category" => $request->request ->get('category'),
                "time" => $request->request->get('time'),
                "ingredients" => $request->request->get('ingredients'),
                "instructions" => $request->request->get('instructions')
            ];
            $session -> set("recipes", $recipes);
            return $this->redirectToRoute('recipe_list');
        }
        return $this->render("cooking_book/add.html.twig");
    }

    #[Route("/delete/{id}", name:"delete_recipe")]
    public function delete_recipe(SessionInterface $session, Request $request, int $id): Response
    {

        if ($request->isMethod('POST')) {
            $recipes = $session->get("recipes", []);
            if (isset($recipes[$id])) {
                unset($recipes[$id]);
                $recipes = array_values($recipes);
                $session->set("recipes", $recipes);
            }
            return $this->redirectToRoute("recipe_list");

        }
        return $this->redirectToRoute("recipe_list");



    }
    #[Route("/edit/{id}", name: 'edit_recipe')]
    public function edit_recipe(SessionInterface $session, Request $request, int $id): Response
    {
        $recipes = $session->get("recipes", []);

        if (!isset($recipes[$id])) {
            return $this->redirectToRoute('recipe_list');
        }

        if ($request->isMethod("POST")) {
            $recipes[$id] = [
                "name" => $request->request->get("name"),
                "category" => $request->request->get("category"),
                "time" => $request->request->get("time"),
                "ingredients" => $request->request->get("ingredients"),
                "instructions" => $request->request->get("instructions")
            ];

            $session->set("recipes", $recipes);

            return $this->redirectToRoute("recipe_list");
        }

        return $this->render("cooking_book/edit.html.twig", [
            "recipe" => $recipes[$id],
            "id" => $id
        ]);
    }
}
