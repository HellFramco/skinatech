import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { ProductosService } from './productos.service';
import { SubcategoriasService } from '../subcategorias/subcategorias.service';

@Component({
  selector: 'app-productos',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './productos.component.html',
  styleUrls: ['./productos.component.css']
})
export class ProductosComponent implements OnInit {
  productos: any[] = [];
  subcategorias: any[] = [];

  mostrarModalCrear = false;
  mostrarModalEditar = false;

  nuevaProducto = {
    nombre: '',
    estado: 'activa',
    subcategoria_id: null as number | null,
    cantidad: null as number | null
  };

  editandoId: number | null = null;
  productoEditado = {
    nombre: '',
    estado: '',
    subcategoria_id: null as number | null,
    cantidad: null as number | null
  };

  constructor(
    private productosService: ProductosService,
    private subcategoriasService: SubcategoriasService,
    private toastr: ToastrService
  ) {}

  ngOnInit() {
    this.cargarProductos();
    this.cargarSubcategorias();
  }

  cargarProductos() {
    this.productosService.getProductos().subscribe({
      next: res => this.productos = res,
      error: () => this.toastr.error('Error al cargar productos')
    });
  }

  cargarSubcategorias() {
    this.subcategoriasService.getSubcategorias().subscribe({
      next: res => this.subcategorias = res,
      error: () => this.toastr.error('Error al cargar subcategorías')
    });
  }

  abrirModalCrear() {
    this.mostrarModalCrear = true;
    this.nuevaProducto = { nombre: '', estado: 'activa', subcategoria_id: null, cantidad: null };
  }

  cerrarModalCrear() {
    this.mostrarModalCrear = false;
  }

  crearProducto() {
    if (!this.nuevaProducto.nombre || !this.nuevaProducto.subcategoria_id || !this.nuevaProducto.cantidad) {
      this.toastr.warning('Completa todos los campos');
      return;
    }

    this.productosService.crearProducto(this.nuevaProducto).subscribe({
      next: () => {
        this.toastr.success('Producto creado correctamente');
        this.cerrarModalCrear();
        this.cargarProductos();
      },
      error: () => this.toastr.error('Error al crear producto')
    });
  }

  abrirModalEditar(producto: any) {
    this.editandoId = producto.id;

    // Tomar datos desde el primer elemento del array subcategorias_con_cantidad
    const subInfo = producto.subcategorias_con_cantidad?.[0] || {};

    this.productoEditado = {
      nombre: producto.nombre,
      estado: producto.estado,
      subcategoria_id: subInfo.subcategoria_id || null,
      cantidad: subInfo.cantidad || null
    };

    this.mostrarModalEditar = true;
  }

  cerrarModalEditar() {
    this.mostrarModalEditar = false;
  }

  guardarEdicion() {
    if (this.editandoId === null) return;

    if (!this.productoEditado.nombre || !this.productoEditado.subcategoria_id || !this.productoEditado.cantidad) {
      this.toastr.warning('Completa todos los campos');
      return;
    }

    this.productosService.actualizarProducto(this.editandoId, this.productoEditado).subscribe({
      next: () => {
        this.toastr.success('Producto actualizado correctamente');
        this.cerrarModalEditar();
        this.cargarProductos();
      },
      error: () => this.toastr.error('Error al actualizar producto')
    });
  }

  eliminarProducto(id: number) {
    if (!confirm('¿Seguro que deseas eliminar este producto?')) return;

    this.productosService.eliminarProducto(id).subscribe({
      next: () => {
        this.toastr.success('Producto eliminado');
        this.cargarProductos();
      },
      error: () => this.toastr.error('Error al eliminar producto')
    });
  }

  activarProducto(id: number) {
    this.productosService.activarProducto(id).subscribe({
      next: () => {
        this.toastr.success('Producto activado');
        this.cargarProductos();
      },
      error: () => this.toastr.error('Error al activar producto')
    });
  }
}
