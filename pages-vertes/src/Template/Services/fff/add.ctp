<!-- File: src/Template/Services/add.ctp -->

<h1>Add Services</h1>
<?php
    echo $this->Form->create($service);
    // Hard code the user for now.
    echo $this->Form->control('service_category_id', ['type' => 'hidden', 'value' => 1]);
    echo $this->Form->control('name');
    echo $this->Form->control('price');
    echo $this->Form->button(__('Save Article'));
    echo $this->Form->end();
?>