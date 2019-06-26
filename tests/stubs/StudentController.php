<?php
class StudentController {
    /**
     * @Route("/student/home")
     */
    public function homeAction() {
        return 'home action';
    }

    /**
     * @Route("/student/about")
     */
    public function aboutAction() {
        return 'about action';
    }

    /**
     * @Route("/student/{id}/{page}")
     */
    public function infoAction()
    {
        return 'info action';
    }
}
