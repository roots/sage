          <div id="menu-dropdown-mini" class="visible-xs hidden-sm hidden-md hidden-lg">
            <div class="select-outer-wrap">
            <div class="select-inner-wrap">
            <?php
            dropdown_menu( array(
                'theme_location' => 'mini',
                
                // You can alter the blanking text eg. "- Menu Name -" using the following
                'dropdown_title' => '-- More --',
            
                // indent_string is a string that gets output before the title of a
                // sub-menu item. It is repeated twice for sub-sub-menu items and so on
                'indent_string' => '- ',
            
                // indent_after is an optional string to output after the indent_string
                // if the item is a sub-menu item
                'indent_after' => ' '
            
            ) );
            ?>
            </div>
            </div>
          </div>