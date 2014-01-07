<?php

/**
 * This is a test3.
 *
 *
<h2>
<a name="textarea" class="anchor" href="#textarea"><span class="octicon octicon-link"></span></a>Textarea</h2>

<p><img src="https://f.cloud.github.com/assets/3412363/1588098/1f194854-5249-11e3-95be-f100bf779ce3.png" alt="Textarea"></p>

<h3>
<a name="arguments-27" class="anchor" href="#arguments-27"><span class="octicon octicon-link"></span></a>Arguments</h3>

<table>
<thead><tr><th>Name</th>
<th>Type</th>
<th>Default</th>
<th>Description.</th>
</tr></thead>
<tbody>
<tr>
<td>type</td>
<td>string</td>
<td>'text'</td>
<td>Controls the field type.</td>
</tr>
<tr>
<td>id</td>
<td>string</td>
<td></td>
<td>Must be unique to all other options.</td>
</tr>
<tr>
<td>title</td>
<td>string</td>
<td></td>
<td>Title of item to be displayed.</td>
</tr>
<tr>
<td>subtitle</td>
<td>string</td>
<td></td>
<td>Subtitle of item to be displayed.</td>
</tr>
<tr>
<td>desc</td>
<td>string</td>
<td></td>
<td>Description of item to be displayed.</td>
</tr>
<tr>
<td>compiler</td>
<td>boolean</td>
<td>false</td>
<td>Flag to run the compiler hook.</td>
</tr>
<tr>
<td>class</td>
<td>string</td>
<td></td>
<td>Append any number of classes to the field.</td>
</tr>
<tr>
<td>required</td>
<td>string/array</td>
<td></td>
<td>Provide the parent and value which affects this field's visibility.</td>
</tr>
<tr>
<td>default</td>
<td>string</td>
<td></td>
<td>Default text.</td>
</tr>
<tr>
<td>validate</td>
<td>string</td>
<td></td>
<td>Controls the entered validation.</td>
</tr>
<tr>
<td>placeholder</td>
<td>string</td>
<td></td>
<td>Message to display when no text is present.</td>
</tr>
<tr>
<td>rows</td>
<td>int</td>
<td>6</td>
<td>Numbers of text rows to display.</td>
</tr>
<tr>
<td>allowed_html</td>
<td>array</td>
<td></td>
<td>array of allowed HTML tags.  See <a href="http://codex.wordpress.org/Function_Reference/wp_kses">http://codex.wordpress.org/Function_Reference/wp_kses</a> for more information.</td>
</tr>
</tbody>
</table>
<h3>
<a name="example-declaration-28" class="anchor" href="#example-declaration-28"><span class="octicon octicon-link"></span></a>Example Declaration</h3>

<div class="highlight"><pre><span class="cp">&lt;?php</span>
    <span class="nv">$fields</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
        <span class="s1">'id'</span><span class="o">=&gt;</span><span class="s1">'7'</span><span class="p">,</span>
        <span class="s1">'type'</span> <span class="o">=&gt;</span> <span class="s1">'textarea'</span><span class="p">,</span>
        <span class="s1">'title'</span> <span class="o">=&gt;</span> <span class="nx">__</span><span class="p">(</span><span class="s1">'Textarea Option - HTML Validated Custom'</span><span class="p">,</span> <span class="s1">'redux-framework-demo'</span><span class="p">),</span> 
        <span class="s1">'subtitle'</span> <span class="o">=&gt;</span> <span class="nx">__</span><span class="p">(</span><span class="s1">'Custom HTML Allowed (wp_kses)'</span><span class="p">,</span> <span class="s1">'redux-framework-demo'</span><span class="p">),</span>
        <span class="s1">'desc'</span> <span class="o">=&gt;</span> <span class="nx">__</span><span class="p">(</span><span class="s1">'This is the description field, again good for additional info.'</span><span class="p">,</span> <span class="s1">'redux-framework-demo'</span><span class="p">),</span>
        <span class="s1">'validate'</span> <span class="o">=&gt;</span> <span class="s1">'html_custom'</span><span class="p">,</span>
        <span class="s1">'default'</span> <span class="o">=&gt;</span> <span class="s1">'&lt;p&gt;Some HTML is allowed in here.&lt;/p&gt;'</span><span class="p">,</span>
        <span class="s1">'allowed_html'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span>
            <span class="s1">'a'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span>
                <span class="s1">'href'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(),</span>
                <span class="s1">'title'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">()</span>
            <span class="p">),</span>
            <span class="s1">'br'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(),</span>
            <span class="s1">'em'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(),</span>
            <span class="s1">'strong'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">()</span>
        <span class="p">)</span>
    <span class="p">);</span>
<span class="cp">?&gt;</span><span class="x"></span>
</pre></div>

<hr>
##Textarea

![Textarea](https://f.cloud.github.com/assets/3412363/1588098/1f194854-5249-11e3-95be-f100bf779ce3.png)

###Arguments

See blow.
| Name        | Type   | Default  | Description.                                |
|-------------|--------|----------|---------------------------------------------|
| type        | string | 'text' | Controls the field type.                    |
| id          | string |          | Must be unique to all other options.        |
| title       | string |          | Title of item to be displayed.              |
| subtitle    | string |          | Subtitle of item to be displayed.           |
| desc        | string |          | Description of item to be displayed.        |
| compiler    | boolean | false   | Flag to run the compiler hook.              |
| class       | string |          | Append any number of classes to the field.  |
| required    | string/array |    | Provide the parent and value which affects this field's visibility. |
| default     | string |          | Default text. |
| validate    | string |           | Controls the entered validation. |
| placeholder | string |           | Message to display when no text is present. |
| rows        | int | 6          | Numbers of text rows to display. |
| allowed_html| array |           | array of allowed HTML tags.  See http://codex.wordpress.org/Function_Reference/wp_kses for more information. |

###Example Declaration
<pre>
    $fields = array(
        'id'=>'7',
        'type' => 'textarea',
        'title' => __('Textarea Option - HTML Validated Custom', 'redux-framework-demo'), 
        'subtitle' => __('Custom HTML Allowed (wp_kses)', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'html_custom',
        'default' => '<p>Some HTML is allowed in here.</p>',
        'allowed_html' => array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array()
        )
    );
</pre> 
<code>
    $fields = array(
        'id'=>'7',
        'type' => 'textarea',
        'title' => __('Textarea Option - HTML Validated Custom', 'redux-framework-demo'), 
        'subtitle' => __('Custom HTML Allowed (wp_kses)', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'html_custom',
        'default' => '<p>Some HTML is allowed in here.</p>',
        'allowed_html' => array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array()
        )
    );
</code> 
 *
 * Here is another.
 *
 * And one more.
 *
 * @internal This file must be parsable by PHP4.
 *
 * @package Redux_Framework
 * @subpackage Fields
 * @access public
 * @global $optname
 * @internal Internal Note string
 * @link http://reduxframework.com
 * @method Test
 * @name $globalvariablename
 * @param   string  $this->field['test']    This is cool.
 * @param   string|boolean  $field[default] Default value for this field.
 * @return Test
 * @see ParentClass
 * @since Redux 3.0.9
 * @todo Still need to fix this!
 * @var string cool
 * @var int notcool
 * @param string[] $options {
 *  @type boolean $required Whether this element is required
 *  @type string  $label    The display name for this element
 * }
 *
 *
 * 
 */



class ReduxFramework_textarea extends ReduxFramework {

    /**
     * Field Constructor.
     *
     *   ## Example Declaration:
     *   <pre>
     *   $fields = array(
     *       'id'=>'7',
     *       'type' => 'textarea',
     *       'title' => __('Textarea Option - HTML Validated Custom', 'redux-framework-demo'), 
     *       'subtitle' => __('Custom HTML Allowed (wp_kses)', 'redux-framework-demo'),
     *       'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
     *       'validate' => 'html_custom',
     *       'default' => '<p>Some HTML is allowed in here.</p>',
     *       'allowed_html' => array(
     *           'a' => array(
     *               'href' => array(),
     *               'title' => array()
     *           ),
     *           'br' => array(),
     *               'em' => array(),
     *               'strong' => array()
     *           )
     *       );
     *   </pre>
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * Example Documentation:
            $fields = array(
                'id'       => 'css_editor',
                'type'     => 'ace_editor',
                'title'    => __('CSS Code', 'redux-framework-demo'), 
                'subtitle' => __('Paste your CSS code here.', 'redux-framework-demo'),
                'mode'     => 'css',
                'theme'    => 'monokai',
                'desc'     => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                'default'  => "#header{\nmargin: 0 auto;\n}"
            );
     *
     * @param $field 
     *
     *  | Name        | Type   | Default  | Description.                                |
        |-------------|--------|----------|---------------------------------------------|
        | type        | string | 'text' | Controls the field type.                    |
        | id          | string |          | Must be unique to all other options.        |
        | title       | string |          | Title of item to be displayed.              |
        | subtitle    | string |          | Subtitle of item to be displayed.           |
        | desc        | string |          | Description of item to be displayed.        |
        | compiler    | boolean | false   | Flag to run the compiler hook.              |
        | class       | string |          | Append any number of classes to the field.  |
        | required    | string/array |    | Provide the parent and value which affects this field's visibility. |
        | default     | string |          | Default text. |
        | validate    | string |           | Controls the entered validation. |
        | placeholder | string |           | Message to display when no text is present. |
        | rows        | int | 6          | Numbers of text rows to display. |
        | allowed_html| array |           | array of allowed HTML tags.  See http://codex.wordpress.org/Function_Reference/wp_kses for more information. |

     * @param $value Constructed by Redux class. Based on the passing in $field['defaults'] value and what is stored in the database.
     * @param $parent ReduxFramework object is passed for easier pointing.
     * @since ReduxFramework 1.0.0
     * @type string $field[test] Description. Default <value>. Accepts <value>, <value>.
    */
    function __construct( $field = array(), $value ='', $parent ) {
    
        //parent::__construct( $parent->sections, $parent->args );
        $this->parent = $parent;
        $this->field = $field;
        $this->value = $value;
    
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.0
    */



    /**
     * Holds configuration settings for each field in a model.
     * Defining the field options
     *
     * array['fields']              array Defines the fields to be shown by scaffolding.
     *          [fieldName]         array Defines the options for a field, or just enables the field if array is not applied.
     *              ['name']        string Overrides the field name (default is the array key)
     *              ['model']       string (optional) Overrides the model if the field is a belongsTo associated value.
     *              ['width']       string Defines the width of the field for paginate views. Examples are "100px" or "auto"
     *              ['align']       string Alignment types for paginate views (left, right, center)
     *              ['format']      string Formatting options for paginate fields. Options include ('currency','nice','niceShort','timeAgoInWords' or a valid Date() format)
     *              ['title']       string Changes the field name shown in views.
     *              ['desc']        string The description shown in edit/create views.
     *              ['readonly']    boolean True prevents users from changing the value in edit/create forms.
     *              ['type']        string Defines the input type used by the Form helper (example 'password')
     *              ['options']     array Defines a list of string options for drop down lists.
     *              ['editor']      boolean If set to True will show a WYSIWYG editor for this field.
     *              ['default']     string The default value for create forms.
     *
     * @param array $arr (See above)
     * @return Object A new editor object.
     **/
    function render() {

        $name = $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']';
        $this->field['placeholder'] = isset($this->field['placeholder']) ? $this->field['placeholder'] : "";
        $this->field['rows'] = isset($this->field['rows']) ? $this->field['rows'] : 6;

        ?><textarea name="<?php echo $name; ?>" id="<?php echo $this->field['id']; ?>-textarea" placeholder="<?php echo esc_attr($this->field['placeholder']); ?>" class="large-text <?php echo $this->field['class']; ?>" rows="<?php echo $this->field['rows']; ?>"><?php echo $this->value; ?></textarea><?php
        
    }
}
