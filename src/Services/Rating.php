<?php
namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Rating extends AbstractController
{
    public function view_star(float $note)
    {
        return "{% if a.note == 0 %}
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 0.5 %}
                                    <i class=\"fa fa-star-half\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 1 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 1.5 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star-half\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 2 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 2.5 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star-half\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 3 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 3.5 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star-half\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 4 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star is-empty\"></i>
                                {% elseif a.note == 4.5 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star-half\"></i>
                                {% elseif a.note == 5 %}
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                    <i class=\"fa fa-star\"></i>
                                {% else %}
                                    <span>error</span>
                                {% endif %}";
    }
}