<?php
class TeacherController {
    /**
     * @Route("/teacher/home")
     */
    public function homeAction() {
        return 'home action';
    }

    /**
     * @Route("/teacher/about")
     */
    public function aboutAction() {
        return 'about action';
    }
}
