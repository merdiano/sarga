<?php

namespace Sarga\Admin\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Sarga\Admin\Http\Firebase;
use Sarga\Shop\Repositories\NotificationRepository;

class PushController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct(protected NotificationRepository $notificationRepository)
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $notification = $this->notificationRepository->findOrFail($id);

        return view($this->_config['view'],compact('notification'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $orderId
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(),[
            'title' => 'required|max:500',
            'content' => 'required|max:3000'
        ]);

        if($note = $this->notificationRepository->create(request()->all())){
            $this->sendNotification($note);

            return redirect()->route($this->_config['redirect']);
        }else {
            session()->flash('error', trans('Error on creating notification'));

            return redirect()->back();
        }
    }

    private function sendNotification($note){
        try {
            $data = $note->only(['id','title','content'])+['type'=>'topic'];
            (new Firebase('/topics/notifications',$data))->send();
            session()->flash('success', trans('Notification sent successfully'));
        }catch (\Exception $ex){
            report($ex);
            session()->flash('error', trans('Notification does not sent'));
        }

    }
}