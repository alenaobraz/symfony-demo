<?php

namespace App\Tests\functional\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public const EMAIL = 'test@example.com';
    public const EMAIL_INVALID = 'test';
    public const PASSWORD = 'password123';
    public const PASSWORD_INVALID = '123';
    public const FIO = 'Иванов Иван Иванович';
    public function testValidRegistration()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        // Select the submit button and get the form
        $form = $crawler->selectButton('Register')->form();

        // Submit the form with valid data
        $form['registration_form[email]'] = self::EMAIL;
        $form['registration_form[plainPassword]'] = self::PASSWORD;
        $form['registration_form[fullName]'] = self::FIO;
        $client->submit($form);

        // Check for redirection
        $this->assertResponseRedirects('/');

        // Check for successful user creation (you might need to adapt this part based on your application)
        $user = $this->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => self::EMAIL]);
        $this->assertNotNull($user);
    }

    public function testInvalidRegistration(): void
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
        $form['registration_form[email]'] = self::EMAIL_INVALID;
        $form['registration_form[plainPassword]'] = '';
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.email.incorrect', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Empty password
        $form['registration_form[email]'] = self::EMAIL;
        $form['registration_form[plainPassword]'] = '';
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.password.blank', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Short password
        $form['registration_form[email]'] = self::EMAIL;
        $form['registration_form[plainPassword]'] = self::PASSWORD_INVALID;
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.password.min', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Empty full name
        $form['registration_form[email]'] = self::EMAIL;
        $form['registration_form[plainPassword]'] = self::PASSWORD;
        $form['registration_form[fullName]'] = '';
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.full_name.blank', [], 'validators')); // Проверка наличия сообщения об ошибке

        // Duplicate email
        $form['registration_form[email]'] = self::EMAIL;
        $form['registration_form[plainPassword]'] = self::PASSWORD;
        $form['registration_form[fullName]'] = self::FIO;
        $client->submit($form);

        $form['registration_form[email]'] = self::EMAIL;
        $form['registration_form[plainPassword]'] = self::PASSWORD;
        $form['registration_form[fullName]'] = self::FIO;
        $client->submit($form);

        $this->assertSelectorTextContains('li', $translator->trans('constraints.email.unique', [], 'validators')); // Проверка наличия сообщения об ошибке
    }
}
