<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;

class OrdersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $orders = $this->Orders->find('all')->contain(['Products'])->toArray();
        
        $this->set([
            'orders'=>$orders,
            '_serialize'=>['orders']
        ]);

    }

    public function view($id = null)
    {
        $order = $this->Orders->find('all')->contain(['Products'])->where(['id'=>$id])->toArray();

        $this->set([
            'order'=>$order,
            '_serialize'=>['order']
        ]);
    }

    public function add()
    {
        $message = '';
        $order_detail = [];
        if($this->request->is('post')) {
            if(empty($this->request->data) || empty($this->request->data['product_id'])) {
                $message = 'Please add proper parameter';
            } else {
                $this->loadModel('OrdersProducts');
                $this->loadModel('Products');

                $product_ids = explode(',', $this->request->data['product_id']);
                foreach ($product_ids as $key=>$value) {
                    if (!is_numeric($value)) {
                        unset($product_ids[$key]);
                    }
                }
                if(!empty($product_ids)) {
                    $products = $this->Products->find()->where(['id IN '=>$product_ids])->all()->toArray();
                    if(count($products) > 0) {
                        $order = $this->Orders->newEntity();
                        $order = $this->Orders->patchEntity($order, $this->request->data);
                        unset($order->product_id);
                        $order->date = date('Y-m-d');
                        $order->customer_id = 1;
                        $order->currency = 'GBP';
                        $order->price = '0';
                        // echo '<pre>';print_r($order);die;
                        if($this->Orders->save($order)) {  
                            $order_id = $order->id;
                            
                            $total_price = 0;
                            foreach($product_ids as $product_id) {
                                $product = $this->OrdersProducts->newEntity(); 

                                $product->order_id = $order_id;
                                $product->product_id = $product_id;
                                $product_array[] = $product;
                                $product_detail = $this->Products->get($product_id);
                                $total_price += $product_detail->price;
                            }
                            if($this->OrdersProducts->saveMany($product_array)) {
                                $this->Orders->updateAll(['price' => $total_price],['id'=> $order_id]);
                                $message = 'Order saved';
                            }
                            $order_detail = $this->Orders->find('all')->contain(['Products'])->where(['id'=>$order_id])->toArray();
                        } else {
                            $message = 'Error in add';
                        }
                    } else {
                        $message = 'product not found';
                    }  
                } else {
                    $message = 'product not found';
                }
            }
        }
        
        $this->set([
            'message' => $message,
            'order_detail' => $order_detail,
            '_serialize' => ['message', 'order_detail']
        ]);
    }
    public function edit($id = null)
    {
        $message = '';
        if ($this->request->is(['post', 'put'])) {
            if(empty($this->request->data) || empty($this->request->data['product_id'])) {
                $message = 'Please add proper parameter';
            } else {
                $exists = $this->Orders->exists(['id' => $id]);
        
                if($exists == true) {
                    $this->loadModel('OrdersProducts');
                    $this->loadModel('Products');
                    $product_ids = explode(',', $this->request->data['product_id']);
                    foreach ($product_ids as $key=>$value) {
                        if (!is_numeric($value)) {
                            unset($product_ids[$key]);
                        }
                    }
                    if(!empty($product_ids)) {
                        $products = $this->Products->find()->where(['id IN '=>$product_ids])->all()->toArray();
                        if(count($products) > 0) {
                            $this->OrdersProducts->deleteAll(['order_id'=>$id]);
                            $total_price = 0;
                            foreach($product_ids as $product_id) {
                                $product = $this->OrdersProducts->newEntity(); 
            
                                $product->order_id = $id;
                                $product->product_id = $product_id;
                                $product_array[] = $product;
                                $product_detail = $this->Products->get($product_id);
                                $total_price += $product_detail->price;
                            }
                            if($this->OrdersProducts->saveMany($product_array)) {
                                $this->Orders->updateAll(['price' => $total_price],['id'=> $id]);
                                $message = 'Order updated';
                            } else {
                                $message = "Error in update";
                            }
                        } else {
                            $message = 'product not found';
                        }  
                    } else {
                        $message = 'product not found';
                    }
                } else {
                    $message = 'not found';
                }
            }
        }

        $this->set([
            'message'=>$message,
            '_serialize'=>['message']
        ]);
    }
    public function delete($id)
    {
        $this->request->allowMethod(['delete']);
        $exists = $this->Orders->exists(['id' => $id]);
        
        if($exists == true) {
            $order = $this->Orders->get($id);
            if($this->Orders->delete($order)) {
                $this->loadModel('OrdersProducts');
                $this->OrdersProducts->deleteAll(['order_id'=>$id]);
                $message = 'Order deleted';
            } else {
                $message = 'Error in delete';
            }
        } else {
            $message = 'not found';
        }
        
        $this->set([
            'message'=>$message,
            '_serialize'=>['message']
        ]);
    }
}
?>