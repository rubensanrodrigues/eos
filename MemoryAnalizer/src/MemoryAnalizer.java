import java.util.Scanner;


public class MemoryAnalizer {
	static public void main(String args[]) {
		MemoryAnalizer ma = new MemoryAnalizer();
		ProcessControl pc = new ProcessControl();
		
		if (args.length == 8) {
			int N = 15;
			int W = 1000;
			int S = (100 * 1024 * 1024);
			int tc = 30;
			int ts = 100;
			int seed = 0;
			
		    try {
		        N = Integer.parseInt(args[0]);
		    } catch (NumberFormatException e) {
		        System.err.println("Argument" + args[0] + " must be an integer.");
		        System.exit(1);
		    }
		    
		    try {
		        W = Integer.parseInt(args[1]);
		    } catch (NumberFormatException e) {
		        System.err.println("Argument" + args[1] + " must be an integer.");
		        System.exit(1);
		    }
		    
		    try {
		        S = Integer.parseInt(args[2]);
		    } catch (NumberFormatException e) {
		        System.err.println("Argument" + args[2] + " must be an integer.");
		        System.exit(1);
		    }
		    
		    try {
		        tc = Integer.parseInt(args[3]);
		    } catch (NumberFormatException e) {
		        System.err.println("Argument" + args[3] + " must be an integer.");
		        System.exit(1);
		    }
		    
		    try {
		        ts = Integer.parseInt(args[4]);
		    } catch (NumberFormatException e) {
		        System.err.println("Argument" + args[4] + " must be an integer.");
		        System.exit(1);
		    }
		    
		    try {
		        seed = Integer.parseInt(args[5]);
		    } catch (NumberFormatException e) {
		        System.err.println("Argument" + args[5] + " must be an integer.");
		        System.exit(1);
		    }
		    
			pc.setParams(N, W, S, tc, ts, seed, args[6], args[7]);
			pc.run();
		}
		else {
			Scanner s = new Scanner(System.in);

			ma.welcome();
			boolean loop = true;
			while (loop) {
				int op = ma.mainMenu(s);
				switch (op) {
				case 1:
					ma.scanValues(pc, s);
					break;
				case 2:
					System.out.print("Iniciado ... ");
					pc.run();
					System.out.println("finalizado!");
					break;
				case 9:
					loop = false;
					break;
				default:
					System.out.println("** OPCAO INVALIDA! **");
					break;
				}
			}
		}
	}
	
	private int mainMenu(Scanner s) {
		System.out.println("+===========================================+");
		System.out.println("|                   MENU                    |");
		System.out.println("+===========================================+");
		System.out.println("|       1 - Configurar parametros           |");
		System.out.println("|       2 - Executar teste                  |");
		System.out.println("|       9 - Encerra aplicativo              |");
		System.out.println("+===========================================+");
		
		return s.nextInt();
		
	}
	
	private void welcome() {
		System.out.println("+===========================================+");
		System.out.println("+  Seja Bem-vindo ao Analisador de Memória  +");
		System.out.println("+===========================================+");
	}
	
	private void scanValues(ProcessControl pc, Scanner s) {
		int N = 15;
		int W = 1000;
		int S = (100*1024*1024);
		int tc = 30;
		int ts = 100;
		int seed = 0;
		
		System.out.print("Entre com um valor para N.....:");
		N = s.nextInt();
		System.out.print("Entre com um valor para W.....:");
		W = s.nextInt();
		//System.out.print("Entre com um valor para S...:");
		//S = s.nextInt();
		System.out.print("Entre com um valor para tc....:");
		tc = s.nextInt();
		//System.out.print("Entre com um valor para ts:...:");
		//ts = s.nextInt();
		System.out.print("Entre com um valor para seed:.:");
		ts = s.nextInt();
		
		pc.setParams(N, W, S, tc, ts, seed, "0", "0");
	}
}
