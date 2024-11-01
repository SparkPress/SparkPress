<?php

use App\Services\StyleService;

beforeEach(function () {
    $this->service = new StyleService();
});

it("returns the expected default tabs", function () {
    $tabs = $this->service->getTabs();
    expect(array_keys($tabs))->toBe(['layout', 'typography', 'background']);
});

it("returns the expected default sections", function () {
    $sections = $this->service->getSections();
    expect(array_keys($sections))->toBe(['spacing', 'sizing', 'positioning']);
});

it("returns the expected default fields", function () {
    $fields = $this->service->getFields();
    expect(array_keys($fields))->toBe(['margin', 'padding', 'width', 'height', 'min_width', 'min_height', 'max_width', 'max_height', 'position', 'top', 'right', 'bottom', 'left', 'z_index']);
});

it('ensures tabs added to custom blocks are returned', function () {
    $this->service->addTab('new_layout', 'Layout', ['test-block']);
    $tabs = $this->service->getTabs('test-block');
    expect(array_keys($tabs))->toBe(['layout', 'typography', 'background', 'new_layout']);
});

it("ensures tabs added to custom blocks are NOT returned for a different block", function() {
    $this->service->addTab('new_layout', 'Layout', ['test-block']);
    $tabs = $this->service->getTabs('another-block');
    expect(array_keys($tabs))->not->toContain('new_layout');
});

it("ensures tabs can be added to multiple blocks", function() {
    $this->service->addTab('new_layout', 'Layout', ['test-block', 'another-block']);
    $anotherBlockTabs = $this->service->getTabs('another-block');
    $testBlockTabs = $this->service->getTabs('test-block');
    $falseBlock = $this->service->getTabs('false-block');
    expect(array_keys($anotherBlockTabs))->toContain('new_layout')
        ->and(array_keys($testBlockTabs))->toContain('new_layout')
        ->and(array_keys($falseBlock))->not->toContain('new_layout');
});

it('ensures sections added to custom blocks are returned', function () {
    $this->service->addSection('new_spacing', 'Spacing', 'layout', ['test-block']);
    $sections = $this->service->getSections('test-block');
    expect(array_keys($sections))->toBe(['spacing', 'sizing', 'positioning', 'new_spacing']);
});

it("ensures sections added to custom blocks are NOT returned for a different block", function() {
    $this->service->addSection('new_spacing', 'Spacing', 'layout', ['test-block']);
    $sections = $this->service->getSections('another-block');
    expect(array_keys($sections))->not->toContain('new_spacing');
});

it("ensures sections can be added to multiple blocks", function() {
    $this->service->addSection('new_spacing', 'Spacing', 'layout', ['test-block', 'another-block']);
    $anotherBlockSections = $this->service->getSections('another-block');
    $testBlockSections = $this->service->getSections('test-block');
    $falseBlockSections = $this->service->getSections('false-block');
    expect(array_keys($anotherBlockSections))->toContain('new_spacing')
        ->and(array_keys($testBlockSections))->toContain('new_spacing')
        ->and(array_keys($falseBlockSections))->not->toContain('new_spacing');
});

it('ensures fields added to custom blocks are returned', function () {
    $this->service->addField('new_margin', 'Margin', 'spacing', 'space', [], null, ['test-block']);
    $fields = $this->service->getFields('test-block');
    expect(array_keys($fields))->toBe(['margin', 'padding', 'width', 'height', 'min_width', 'min_height', 'max_width', 'max_height', 'position', 'top', 'right', 'bottom', 'left', 'z_index', 'new_margin']);
});

it("ensures fields added to custom blocks are NOT returned for a different block", function() {
    $this->service->addField('new_margin', 'Margin', 'spacing', 'space', [], null, ['test-block']);
    $fields = $this->service->getFields('another-block');
    expect(array_keys($fields))->not->toContain('new_margin');
});

it("ensures fields can be added to multiple blocks", function() {
    $this->service->addField('new_margin', 'Margin', 'spacing', 'space', [], null, ['test-block', 'another-block']);
    $anotherBlockFields = $this->service->getFields('another-block');
    $testBlockFields = $this->service->getFields('test-block');
    $falseBlockFields = $this->service->getFields('false-block');
    expect(array_keys($anotherBlockFields))->toContain('new_margin')
        ->and(array_keys($testBlockFields))->toContain('new_margin')
        ->and(array_keys($falseBlockFields))->not->toContain('new_margin');
});

it("returns the expected structure for a default block", function () {
    $service = new StyleService();
    $style = $service->getStyle("default-block");
    expect(array_keys($style))->toBe(['layout', 'typography', 'background'])
        ->and(array_keys($style['layout']['sections']))->toBe(['spacing', 'sizing', 'positioning']);
});

it("returns the expected structure for a custom block", function () {
    $this->service->addTab('new_layout', 'Layout', ['test-block']);
    $this->service->addSection('new_spacing', 'Spacing', 'new_layout', ['test-block']);
    $this->service->addField('new_margin', 'Margin', 'new_spacing', 'space', [], null, ['test-block']);
    $style = $this->service->getStyle("test-block");

    expect(array_keys($style))->toContain('new_layout')
        ->and(array_keys($style['layout']['sections']))->toContain('spacing', 'sizing', 'positioning')
        ->and(array_keys($style['new_layout']['sections']['new_spacing']['fields']))->toBe(['new_margin']);
});
