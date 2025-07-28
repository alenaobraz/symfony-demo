<?php

namespace App\Tests\functional\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistration()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        // Select the submit button and get the form
        $form = $crawler->selectButton('Register')->form();

        // Submit the form with valid data
        $form['registration_form[email]'] = 'test2@example.com';
        $form['registration_form[plainPassword]'] = 'password123';
        $form['registration_form[fullName]'] = 'Иванов Иван Иванович';
        $client->submit($form);

        // Check for redirection
        $this->assertResponseRedirects('/');

        // Check for successful user creation (you might need to adapt this part based on your application)
        $user = $this->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => 'test2@example.com']);
        $this->assertNotNull($user);
    }

    public function testSubmitInvalidData(): void
    {
        // отключает автоудаление созданных тестовых данных (чтобы проверить на дубликаты)
        //StaticDriver::setKeepStaticConnections(false);

        $client = static::createClient();
        $translator = static::getContainer()->get('translator');

        $crawler = $client->request('GET', '/register');

        // Select the submit button and get the form
        $form = $crawler->selectButton('Register')->form();

        // Submit the form with invalid data

        // Empty email
        $form['registration_form[email]'] = '';
        $form['registration_form[plainPassword]'] = '';
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.email.blank', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Incorrect email
        $form['registration_form[email]'] = 'test';
        $form['registration_form[plainPassword]'] = '';
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.email.incorrect', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Empty password
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[plainPassword]'] = '';
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.password.blank', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Short password
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[plainPassword]'] = '123';
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.password.min', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Empty full name
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[plainPassword]'] = 'password123';
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.full_name.blank', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Duplicate email
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[plainPassword]'] = 'password123';
        $form['registration_form[fullName]'] = 'Иванов Иван Иванович';
        $client->submit($form);

        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[plainPassword]'] = 'password123';
        $form['registration_form[fullName]'] = 'Иванов Иван Иванович';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.email.unique', [], 'validators')); // Проверка наличия сообщения об ошибке
    }
}
