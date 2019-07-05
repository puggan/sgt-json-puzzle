all: unruly
unruly: bin/unrulysolver c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/unruly.c
bin/unrulysolver: bin/ c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/unruly.c
	gcc -D STANDALONE_SOLVER c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/unruly.c -o bin/unrulysolver
bin/:
	mkdir bin
