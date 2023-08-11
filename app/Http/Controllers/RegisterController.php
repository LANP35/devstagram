<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }



    //Se agrega la variable de Request
    public function store(Request $request)
    {
        //dd imprime, pero detiene la ejecución de laravel
        //Con el get se obtiene el valor del name en el formulario
        //dd($Request->get('username'));
    
        $request->request->add(['username'=>Str::slug($request->username)]);

        //Validación
        $this->validate(
            $request,
            [
                //Pide que el campo name sea obligatorio (Por el atributo name),
                //asimismo pide que el numero de digitos sea minimo de 5
                'name' => 'required|max:30',
                'username' => 'required|unique:users|min:3|max:20',
                'email' => 'required|unique:users|email|max:60',
                'password' => 'required|confirmed|min:6'
            ]
        );

        //Crear nuevo registro
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' =>  Hash::make($request->password)
        ]);


        //Autenticar usuario
        auth()->attempt([
            'email'=>$request->email,
            'password'=>$request->password
        ]);


        
        //Redireccionar
        //return redirect()->route('posts.index');
        return redirect()->route('posts.index', auth()->user()->username);
        


    }
}
