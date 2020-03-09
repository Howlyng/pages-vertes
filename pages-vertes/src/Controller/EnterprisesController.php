<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Enterprises Controller
 *
 *
 * @method \App\Model\Entity\Enterprise[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EnterprisesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->set('isOwner',false);
//        $this->set(compact('enterprises'));
    }

    /**
     * View method (public)
     *
     * @param string|null $id Enterprise id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ($id == null || !$this->Enterprises->exists(['id' => $id])) {
            $this->Flash->error(__d("Employees", 'Sorry, this enterprise does not exist.'));
            $this->redirect('/');
            return;
        } else {
            $this->set('isOwner',false);
            $enterprise = $this->Enterprises->get($id, [
                'contain' => 'Pictures'
            ]);
            $this->set('enterprise', $enterprise);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $enterprise = $this->Enterprises->newEntity();
        if ($this->request->is('post')) {
            $enterprise = $this->Enterprises->patchEntity($enterprise, $this->request->getData());
            if ($this->Enterprises->save($enterprise)) {
                $this->Flash->success(__('The enterprise has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The enterprise could not be saved. Please, try again.'));
        }
        $this->set(compact('enterprise'));
    }

    /**
     * Edit method
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $this->set('isOwner', true);

        $user = $this->Auth->user()["id"];
        $user = TableRegistry::get('Users')->get($user, ['contain' => 'Enterprises']);

        $enterprise_id = $user['enterprise']['id'];
        $enterprise = $this->Enterprises->get($enterprise_id, [
            'contain' => 'Pictures'
        ]);
        $picture = TableRegistry::get('Pictures')->get(['id' => $enterprise->picture->id]);


        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $pictureReady = true; //La picture existe déjà

            //Nettoyer les champs saisis
            if (isset($data['name']) && isset($data['description'])) {
                $data['name'] = h($data['name']);
                $data['description'] = h($data['description']);
            }

            //$this->nicePrintR($data);

            if ($data && sizeof($data['picture']) != 0 && $data['picture']['tmp_name'] != null) {
                $picture->base64image = base64_encode(file_get_contents($data['picture']['tmp_name']));
                $picture->base64image = "data:" . $data['picture']['type'] . ";base64," . $picture->base64image;
                if (!TableRegistry::get('Pictures')->save($picture)) {
                    $this->Flash->error(__('The enterprise could not be saved. Please, try again.'));
                    $pictureReady = false;
                }
            }

            //Gestion d'image avant de sauver l'employé
            if ($pictureReady) {
                $enterprise = $this->Enterprises->patchEntity($enterprise, $data);
                if ($this->Enterprises->save($enterprise)) {
                    $this->Flash->success(__('The informations has been saved.'));

                    return $this->redirect(['url' => '/my-enterprise/edit']);
                }
                $this->Flash->error(__('The enterprise could not be saved. Please, try again.'));
            }


        }
//        $this->set('enterprise', $enterprise);
        $this->set(compact('enterprise'));
        $this->set('user', $user);
        return $this->render('view');
    }

    /**
     * Delete method
     *
     * @param string|null $id Enterprise id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $enterprise = $this->Enterprises->get($id);
        if ($this->Enterprises->delete($enterprise)) {
            $this->Flash->success(__('The enterprise has been deleted.'));
        } else {
            $this->Flash->error(__('The enterprise could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
