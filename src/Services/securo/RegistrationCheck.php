<?php

namespace App\Services\securo;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationCheck extends AbstractController
{
    public function securityCheck($email, $tel)
    {
        /*
         * code 0 : if no problems with registration
         * code 100 : if email already exist in data base
         * code 200 : if phone number already exist in data base
         * code 300 : if phone number and email already exist in data base
         * code 400 : if phone number and/or email is empty
         */

        $code = 0;
        $entityRepository = $this->getDoctrine()->getRepository(User::class);
        if($email != "" && $tel != "")
        {
            if(!($entityRepository->findOneBy(["email" => $email])) && !$entityRepository->findOneBy(["telephone" => $tel]))
            {
                $code = 0;
            }
            else if($entityRepository->findOneBy(["email" => $email]) && !($entityRepository->findOneBy(["telephone" => $tel])))
            {
                $code = 100;
            }
            else if(!($entityRepository->findOneBy(["email" => $email])) && ($entityRepository->findOneBy(["telephone" => $tel])))
            {
                $code = 200;
            }
            else if($entityRepository->findOneBy(["email" => $email]) && ($entityRepository->findOneBy(["telephone" => $tel])))
            {
                $code = 300;
            }

        }
        else
        {
            $code = 400;
        }

        return $code;
    }
}