<?php

declare(strict_types=1);

namespace Drupal\Tests\orbit_webform\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\webform\Entity\Webform;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests Orbit Webform install behavior.
 *
 * @group orbit_webform
 * @runTestsInSeparateProcesses
 */
#[RunTestsInSeparateProcesses]
class OrbitWebformInstallTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'field',
    'file',
    'path',
    'path_alias',
    'captcha',
    'webform',
    'orbit_webform',
  ];

  /**
   * The allowed Webform element types.
   *
   * @var string[]
   */
  protected array $allowedElementTypes = [
    'textfield',
    'textarea',
    'email',
    'tel',
    'number',
    'select',
    'checkbox',
    'checkboxes',
    'radios',
    'date',
    'captcha',
    'webform_actions',
    'webform_markup',
    'managed_file',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('file');
    $this->installEntitySchema('path_alias');
    $this->installSchema('captcha', ['captcha_sessions']);
    $this->installSchema('webform', ['webform']);
    $this->installConfig(['captcha', 'webform', 'orbit_webform']);

    // Re-apply the restriction after Webform's default config has been
    // installed in the Kernel test environment.
    \Drupal::moduleHandler()->loadInclude('orbit_webform', 'install');
    orbit_webform_limit_webform_element_types();
  }

  /**
   * Tests the example form installed by Orbit Webform.
   */
  public function testFormElementsWebformIsInstalled(): void {
    $webform = Webform::load('form_elements');

    $this->assertNotNull($webform);
    $this->assertSame('Form elements', $webform->label());

    $elements = $webform->getElementsDecodedAndFlattened();
    $actual_element_types = array_column($elements, '#type');

    foreach ($this->allowedElementTypes as $element_type) {
      $this->assertContains($element_type, $actual_element_types);
    }
  }

  /**
   * Tests Webform element restrictions.
   */
  public function testOnlyAllowedElementsAreAvailable(): void {
    $excluded_element_types = $this->config('webform.settings')->get('element.excluded_elements');

    foreach ($this->allowedElementTypes as $element_type) {
      $this->assertArrayNotHasKey($element_type, $excluded_element_types);
    }

    $this->assertArrayHasKey('webform_address', $excluded_element_types);
    $this->assertArrayHasKey('webform_name', $excluded_element_types);
  }

  /**
   * Tests default purge settings for newly created Webforms.
   */
  public function testNewWebformsGetDefaultPurgeSettings(): void {
    $webform = Webform::create([
      'id' => 'purge_test',
      'title' => 'Purge test',
    ]);
    $webform->save();

    $this->assertSame('all', $webform->getSetting('purge'));
    $this->assertSame(30, $webform->getSetting('purge_days'));
  }

}
