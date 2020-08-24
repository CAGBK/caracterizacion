<?php

namespace App\Http\Controllers\Reporte;

use App\Model\Reporte\Reporte;
use Illuminate\Http\Request;
use App\Model\Caracterizacion\Caracterizacion;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\Estado;
use App\Rol;
use App\Model\Caracterizacion\Unidad;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $viabilidad_obtenida = $request->get('viabilidad');	
        $viabilidades = array("Consultar con jefatura servicio médico y SST", "Viable trabajo presencial", "Trabajo en casa y consultar a telemedicina", "Trabajo en casa", "Sin clasificación");
        $unidad_obtenida = $request->get('unidad');	
        $rol_obtenido = $request->get('rol');	
        $estado_obtenido = $request->get('estado');
        $unidades = Unidad::all();
        $roles = Rol::all();
        $caracterizaciones;
        $caracterizaciones = Caracterizacion::all();
        $user = Auth::user();
        $caracterizaciones = $this->busquedaAvanzada($request); 
        $caracterizaciones = $this->agregarColorEstado($caracterizaciones); 
        if($user->rol_id < 3){
          $caracterizaciones  = $caracterizaciones->filter(function ($caracterizacion, $key){
            $user = Auth::user();
            return $caracterizacion->user->unidad_id == $user->unidad_id;
          });
          $unidades = Unidad::where( 'id','=', Auth::user()->unidad_id )->get();
          $roles = Rol::where( 'id','=', Auth::user()->rol_id )->get();
        }
        $caracterizaciones = $caracterizaciones->paginate(10);
        $estados = Estado::all();
        return view('reporte.index', compact('viabilidades','roles','unidades', 'estados' ,'unidad_obtenida', 'estado_obtenido' , 'rol_obtenido' , 'viabilidad_obtenida'), ['caracterizaciones' => $caracterizaciones->paginate(15)]);
    }
    /**
     * @param Caracterizacion|Collection[] $caracterizaciones
     * @return Caracterizacion|Collection[]
     */
    public function agregarColorEstado($caracterizaciones )
    {
      $caracterizaciones = $caracterizaciones->each(function($caracterizacion, $key){
        switch ( $caracterizacion->viabilidad_caracterizacion ) {
          case 'Consultar con jefatura servicio médico y SST':
            $caracterizacion->estadoColor = "viabilidad-sst bold";
            break;
          case 'Viable trabajo presencial':
            $caracterizacion->estadoColor = "viabilidad-tp bold";
            break;
          case 'Trabajo en casa y consultar a telemedicina':
            $caracterizacion->estadoColor = "viabilidad-tele bold";
            break;
          case 'Trabajo en casa':
            $caracterizacion->estadoColor = "viabilidad-tec  bold";
            break;
          case 'Sin clasificación':
            $caracterizacion->estadoColor = "viabilidad-sin bold";
            break;
          default:
            break;
        }
        return $caracterizacion;
      });
      return $caracterizaciones;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function busquedaAvanzada($request){

      if( null !==  $request ){
        
          if (Auth::user()->rol_id >= 2){	        
            $viabilidad_obtenida = $request->get('viabilidad');	
            $unidad_obtenida = $request->get('unidad');	
            $rol_obtenido = $request->get('rol');	
            $estado_obtenido = $request->get('estado');	
            $caracterizacion = Caracterizacion::first();
            $caracterizacion = $caracterizacion->join('users', 'users.id', '=', 'caracterizacion.user_id');	
            if($unidad_obtenida != ""){	
                $caracterizacion = $caracterizacion->where('unidad_id', '=', $unidad_obtenida);	
            }	
            if($rol_obtenido != ""){	
                $caracterizacion = $caracterizacion->where('rol_id', '=', $rol_obtenido);	

            }	
            if($estado_obtenido != ""){	
                $caracterizacion = $caracterizacion->where('estado_id', '=', $estado_obtenido);	
            }	
            if($viabilidad_obtenida != ""){	
              $caracterizacion = $caracterizacion->where('viabilidad_caracterizacion', '=', $viabilidad_obtenida);	

          }	
            $caracterizacion = $caracterizacion->paginate(10);	
        }	
      }	
      else{	
        $caracterizacion = Caracterizacion::all();
      }

        return $caracterizacion;
    }

    /**
     * Display a listing of the resource graph.
     *
     * @return \Illuminate\Http\Response
     */
    public function grafico()
    {
        $caracterizaciones;
        $caracterizaciones = Caracterizacion::all();
        $user = Auth::user();
        if($user->rol_id < 3){
          $caracterizaciones  = $caracterizaciones->filter(function ($caracterizacion, $key){
            $user = Auth::user();
            return $caracterizacion->user->unidad_id == $user->unidad_id;
          });
        }

        return view('reporte.grafico', ['caracterizaciones' => $caracterizaciones->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function show(Reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function edit(Reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reporte $reporte)
    {
        //
    }
}
