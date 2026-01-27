#usuario

create table usuario(
	id int auto_increment primary key,
	username varchar(255) not null,
	email varchar(255) not null,
	password varchar(255) not null,
	rol int not null,
	activo boolean default true
);

#jugador de padel

create table jugador(
	id int auto_increment primary key,
	id_api int not null unique,
	nombre varchar(250) not null,
	sexo varchar(2) null,
	altura int null,
	puntos int null,
	imagen_url varchar(500) null,
	activo boolean default true
);

create table jugadores_favoritos(
	id int auto_increment primary key,
	id_usuario int not null,
	id_jugador int not null,
	favorito boolean default true,
	foreign key (id_usuario) references usuario(id) on delete restrict,
	foreign key (id_jugador) references jugador(id) on delete restrict
);

#review

create table review(
	id int auto_increment primary key,
	id_usuario int not null,
	id_jugador int not null,
	puntuacion int not null,
	texto varchar(600) null,
	activo boolean default true,
	foreign key (id_usuario) references usuario(id) on delete restrict,
	foreign key (id_jugador) references jugador(id) on delete restrict
);

#categoria

create table categoria(
	id int auto_increment primary key,
	nombre varchar(100) not null,
	activo boolean default true
);

#categoria de los jugadores

create table jugador_categoria(
	id int auto_increment primary key,
	id_jugador int not null,
	id_categoria int not null,
	foreign key (id_jugador) references jugador(id) on delete restrict,
	foreign key (id_categoria) references categoria(id) on delete restrict
);

#ranking general (se crea usando la categoria)

create table ranking_general(
	id int auto_increment primary key,
	id_categoria int references categoria(id) on delete restrict,
	titulo varchar(200) not null,	
	descripcion varchar(600) not null,
	activo boolean default true
);

#ranking personal de cada usuario

create table ranking_personal(
	id int auto_increment primary key,
	id_usuario int not null,
	id_ranking_general int,
	activo boolean default true,
	foreign key (id_usuario) references usuario(id) on delete restrict,
	foreign key (id_ranking_general) references ranking_general(id) on delete restrict
);

#orden del ranking personal(la posicion en la que el usuario pone a cada jugador)

create table orden_ranking_personal(
	id int auto_increment primary key,
	id_ranking_personal int not null,
	id_jugador int not null,
	posicion int not null,
	foreign key (id_ranking_personal) references ranking_personal(id) on delete restrict,
	foreign key (id_jugador) references jugador(id) on delete restrict
);





