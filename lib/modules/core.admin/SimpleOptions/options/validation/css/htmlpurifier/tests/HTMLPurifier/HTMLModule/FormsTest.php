<?php

class HTMLPurifier_HTMLModule_FormsTest extends HTMLPurifier_HTMLModuleHarness
{

    function setUp() {
        parent::setUp();
        $this->config->set('HTML.Trusted', true);
        $this->config->set('Attr.EnableID', true);
    }

    function testBasicUse() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult( // need support for label for later
            '
<form action="http://somesite.com/prog/adduser" method="post">
    <p>
    <label>First name: </label>
              <input type="text" id="firstname" /><br />
    <label>Last name: </label>
              <input type="text" id="lastname" /><br />
    <label>email: </label>
              <input type="text" id="email" /><br />
    <input type="radio" name="sex" value="Male" /> Male<br />
    <input type="radio" name="sex" value="Female" /> Female<br />
    <input type="submit" value="Send" /> <input type="reset" />
    </p>
</form>'
        );
    }

    function testSelectOption() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('
<form action="http://somesite.com/prog/component-select" method="post">
   <p>
   <select multiple="multiple" size="4" name="component-select">
      <option selected="selected" value="Component_1_a">Component_1</option>
      <option selected="selected" value="Component_1_b">Component_2</option>
      <option>Component_3</option>
      <option>Component_4</option>
      <option>Component_5</option>
      <option>Component_6</option>
      <option>Component_7</option>
   </select>
   <input type="submit" value="Send" /><input type="reset" />
   </p>
</form>
        ');
    }

    function testSelectOptgroup() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('
<form action="http://somesite.com/prog/someprog" method="post">
<p>
 <select name="ComOS">
     <option selected="selected" label="none" value="none">None</option>
     <optgroup label="PortMaster 3">
       <option label="3.7.1" value="pm3_3.7.1">PortMaster 3 with ComOS 3.7.1</option>
       <option label="3.7" value="pm3_3.7">PortMaster 3 with ComOS 3.7</option>
       <option label="3.5" value="pm3_3.5">PortMaster 3 with ComOS 3.5</option>
     </optgroup>
     <optgroup label="PortMaster 2">
       <option label="3.7" value="pm2_3.7">PortMaster 2 with ComOS 3.7</option>
       <option label="3.5" value="pm2_3.5">PortMaster 2 with ComOS 3.5</option>
     </optgroup>
     <optgroup label="IRX">
       <option label="3.7R" value="IRX_3.7R">IRX with ComOS 3.7R</option>
       <option label="3.5R" value="IRX_3.5R">IRX with ComOS 3.5R</option>
     </optgroup>
 </select>
</p>
</form>
        ');
    }

    function testTextarea() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('
<form action="http://somesite.com/prog/text-read" method="post">
   <p>
   <textarea name="thetext" rows="20" cols="80">
   First line of initial text.
   Second line of initial text.
   </textarea>
   <input type="submit" value="Send" /><input type="reset" />
   </p>
</form>
        ');
    }

    // label tests omitted

    function testFieldset() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('
<form action="..." method="post">
 <fieldset>
  <legend>Personal Information</legend>
  Last Name: <input name="personal_lastname" type="text" tabindex="1" />
  First Name: <input name="personal_firstname" type="text" tabindex="2" />
  Address: <input name="personal_address" type="text" tabindex="3" />
  ...more personal information...
 </fieldset>
 <fieldset>
  <legend>Medical History</legend>
  <input name="history_illness" type="checkbox" value="Smallpox" tabindex="20" />Smallpox
  <input name="history_illness" type="checkbox" value="Mumps" tabindex="21" /> Mumps
  <input name="history_illness" type="checkbox" value="Dizziness" tabindex="22" /> Dizziness
  <input name="history_illness" type="checkbox" value="Sneezing" tabindex="23" /> Sneezing
  ...more medical history...
 </fieldset>
 <fieldset>
  <legend>Current Medication</legend>
  Are you currently taking any medication?
  <input name="medication_now" type="radio" value="Yes" tabindex="35" />Yes
  <input name="medication_now" type="radio" value="No" tabindex="35" />No

  If you are currently taking medication, please indicate
  it in the space below:
  <textarea name="current_medication" rows="20" cols="50" tabindex="40"></textarea>
 </fieldset>
</form>
        ');
    }

    function testInputTransform() {
        $this->config->set('HTML.Doctype', 'XHTML 1.0 Strict');
        $this->assertResult('<input type="checkbox" />', '<input type="checkbox" value="" />');
    }

    function testTextareaTransform() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('<textarea></textarea>', '<textarea cols="22" rows="3"></textarea>');
    }

    function testTextInFieldset() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('<fieldset>   <legend></legend>foo</fieldset>');
    }

    function testStrict() {
        $this->config->set('HTML.Doctype', 'HTML 4.01 Strict');
        $this->assertResult('<form action=""></form>', '');
    }

    function testLegacy() {
        $this->assertResult('<form action=""></form>');
        $this->assertResult('<form action=""><input align="left" /></form>');
    }

}

// vim: et sw=4 sts=4
