<?php

class CustomerService extends AbstractService {
    protected $tableName = 'customer';
    protected $columns = [
        'id',
        'firstname',
        'lastname',
        'address',
        'zipcode',
        'city',
    ];
}