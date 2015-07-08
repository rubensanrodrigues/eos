public class ProcessControl {
	private int N = 74;
	private int W = 1000;
	private int S = (4*1024*1024);
	private int tc = 10;
	private int ts = 5;
	private int seed = 0;
	private int pidGetStat;
	private String exp = "0";
	private String rep = "0";
	private Process[] childs;

	public ProcessControl() {
		pidGetStat = N;
		this.childs = new Process[N+1];
	}

	public void setParams(int N, int W, int S, int tc, int ts, int seed, String exp, String rep) {
		this.N = N;
		this.W = W;
		this.S = S;
		this.tc = tc;
		this.ts = ts;
		this.seed = seed;
		this.exp = exp;
		this.rep = rep;

		pidGetStat = N;
		this.childs = new Process[N+1];

	}

	private String formatProcessCall(int m_id) {
		return String.format("./loadsys %s %s %s %s %s %s %s", m_id, S, W, ts, seed, exp, rep);
	}

	public void run() {
		int i;

		try {
			childs[pidGetStat] = Runtime.getRuntime().exec(String.format("./getstats %s %s", exp, rep));
			Thread.sleep(3000);
		}
		catch (Exception e) {
			e.printStackTrace();
		}

		for (i=0; i<N; i++) {
			try {
				String command = formatProcessCall(i);
				childs[i] = Runtime.getRuntime().exec(command);
				Thread.sleep(tc*10);
			}
			catch (InterruptedException ex) {
			    Thread.currentThread().interrupt();
			}
			catch (Exception e) {
				e.printStackTrace();
			}
		}

		try {
			//10^3 time creation
			Thread.sleep((N*tc)*1000);
		}
		catch (InterruptedException ex) {
			Thread.currentThread().interrupt();
		}

		for (i=0; i<N; i++) {
			childs[i].destroy();
		}

		try {
			// 60.000 = 60s/1min
			Thread.sleep(60000);
		}
		catch (InterruptedException ex) {
			Thread.currentThread().interrupt();
		}

		childs[pidGetStat].destroy();
	}
}
